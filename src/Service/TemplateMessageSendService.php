<?php

namespace WechatMiniProgramSubscribeMessageBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Enum\MiniProgramState;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;

#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class TemplateMessageSendService
{
    public function __construct(
        private readonly SubscribeTemplateRepository $templateRepository,
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 获取指定账号的可用模板列表
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAvailableTemplates(?int $accountId = null): array
    {
        $criteria = ['valid' => true];
        if (null !== $accountId) {
            $account = $this->accountRepository->find($accountId);
            if (null !== $account) {
                $criteria['account'] = $account;
            }
        }

        $templates = $this->templateRepository->findBy($criteria, ['title' => 'ASC']);

        $result = [];
        foreach ($templates as $template) {
            $result[] = [
                'id' => $template->getId(),
                'title' => $template->getTitle(),
                'priTmplId' => $template->getPriTmplId(),
                'content' => $template->getContent(),
                'type' => $template->getType()?->value,
                'accountName' => $template->getAccount()?->getName(),
                'params' => $this->formatTemplateParams($template),
            ];
        }

        return $result;
    }

    /**
     * 获取指定模板的参数信息
     *
     * @return array<int, array<string, mixed>>
     */
    public function getTemplateParams(int $templateId): array
    {
        $template = $this->templateRepository->find($templateId);
        if (null === $template) {
            return [];
        }

        return $this->formatTemplateParams($template);
    }

    /**
     * 发送模板消息
     *
     * @param array<string, mixed> $data
     * @return array{success: bool, message: string, response?: array<string, mixed>}
     */
    public function sendTemplateMessage(
        int $templateId,
        string $unionId,
        array $data,
        ?string $page = null,
        ?string $miniProgramState = null,
        ?string $lang = null,
    ): array {
        $template = $this->templateRepository->find($templateId);
        if (null === $template || false === $template->isValid()) {
            return [
                'success' => false,
                'message' => '模板不存在或已失效',
            ];
        }

        $account = $template->getAccount();
        if (null === $account) {
            return [
                'success' => false,
                'message' => '模板关联的账号不存在',
            ];
        }

        // 构建发送请求
        $request = $this->buildSendRequest($template, $unionId, $data, $page, $miniProgramState, $lang);

        try {
            $response = $this->client->request($request);

            // 验证响应数据类型
            if (!is_array($response)) {
                $this->logger->warning('微信API返回的响应数据格式不正确', [
                    'template' => $template->getId(),
                    'unionId' => $unionId,
                    'response_type' => gettype($response),
                ]);

                $this->logSendResult($template, $unionId, $data, null, false, '响应数据格式不正确');

                return [
                    'success' => false,
                    'message' => '发送失败：响应数据格式不正确',
                ];
            }

            // 确保响应数据符合类型要求
            $normalizedResponse = $this->normalizeResponse($response);
            $this->logSendResult($template, $unionId, $data, $normalizedResponse, true);

            return [
                'success' => true,
                'message' => '发送成功',
                'response' => $normalizedResponse,
            ];
        } catch (\Throwable $exception) {
            $this->logger->error('发送模板消息失败', [
                'template' => $template->getId(),
                'unionId' => $unionId,
                'data' => $data,
                'exception' => $exception->getMessage(),
            ]);

            $this->logSendResult($template, $unionId, $data, null, false, $exception->getMessage());

            return [
                'success' => false,
                'message' => '发送失败：' . $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function formatTemplateParams(SubscribeTemplate $template): array
    {
        $params = [];
        foreach ($template->getParams() as $param) {
            $params[] = [
                'code' => $param->getCode(),
                'type' => $param->getType()?->value,
                'typeLabel' => $param->getType()?->getLabel(),
                'enumValues' => $param->getEnumValues(),
                'maxLength' => $param->getType()?->getMaxLength(),
            ];
        }

        return $params;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function buildSendRequest(
        SubscribeTemplate $template,
        string $unionId,
        array $data,
        ?string $page,
        ?string $miniProgramState,
        ?string $lang,
    ): SendSubscribeMessageRequest {
        $request = new SendSubscribeMessageRequest();

        $account = $template->getAccount();
        if (null === $account) {
            throw new \RuntimeException('Template must have an associated account');
        }
        $request->setAccount($account);

        $templateId = $template->getPriTmplId();
        if (null === $templateId) {
            throw new \RuntimeException('Template must have a template ID');
        }
        $request->setTemplateId($templateId);

        $request->setToUser($unionId);
        $request->setData($this->formatMessageData($data));

        if (null !== $page) {
            $request->setPage($page);
        }

        if (null !== $miniProgramState) {
            $state = MiniProgramState::tryFrom($miniProgramState);
            if (null !== $state) {
                $request->setMiniProgramState($state);
            }
        }

        if (null !== $lang) {
            $request->setLang($lang);
        }

        return $request;
    }

    /**
     * 格式化消息数据为微信API要求的格式
     *
     * @param array<string, mixed> $data
     * @return array<string, array<string, string>>
     */
    private function formatMessageData(array $data): array
    {
        $formatted = [];
        foreach ($data as $key => $value) {
            // 安全地将值转换为字符串
            $stringValue = '';
            if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $stringValue = (string) $value;
            } elseif (is_array($value)) {
                $encoded = json_encode($value);
                $stringValue = $encoded !== false ? $encoded : '';
            } else {
                $stringValue = gettype($value);
            }

            $formatted[$key] = [
                'value' => $stringValue,
            ];
        }

        return $formatted;
    }

    /**
     * 记录发送结果
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed>|null $response
     */
    private function logSendResult(
        SubscribeTemplate $template,
        string $unionId,
        array $data,
        ?array $response,
        bool $success,
        ?string $errorMessage = null,
    ): void {
        $log = new SendSubscribeLog();
        $log->setSubscribeTemplate($template);
        $log->setAccount($template->getAccount());

        $templateId = $template->getPriTmplId();
        if (null === $templateId) {
            throw new \RuntimeException('Template must have a template ID for logging');
        }
        $log->setTemplateId($templateId);

        $log->setRemark("后台手动发送给 unionId: {$unionId}");

        $log->setResult($this->encodeLogResult($success, $response, $errorMessage, $data));

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * 编码日志结果
     *
     * @param array<string, mixed>|null $response
     * @param array<string, mixed> $data
     */
    private function encodeLogResult(
        bool $success,
        ?array $response,
        ?string $errorMessage,
        array $data,
    ): string {
        if ($success && null !== $response) {
            $result = json_encode($response);
            if (false === $result) {
                return $this->encodeErrorData('Failed to encode success response', ['original_data' => $response]);
            }

            return $result;
        }

        return $this->encodeErrorData($errorMessage ?? '发送失败', ['data' => $data]);
    }

    /**
     * 编码错误数据
     *
     * @param array<string, mixed> $additionalData
     */
    private function encodeErrorData(string $message, array $additionalData = []): string
    {
        $errorData = array_merge(['error' => true, 'message' => $message], $additionalData);
        $result = json_encode($errorData);

        return false !== $result ? $result : '{"error":true,"message":"Failed to encode error response"}';
    }

    /**
     * 标准化响应数据为符合类型要求的格式
     *
     * @param array<mixed> $response
     * @return array<string, mixed>
     */
    private function normalizeResponse(array $response): array
    {
        $normalized = [];
        foreach ($response as $key => $value) {
            $stringKey = is_string($key) ? $key : (string) $key;
            $normalized[$stringKey] = $value;
        }
        return $normalized;
    }
}
