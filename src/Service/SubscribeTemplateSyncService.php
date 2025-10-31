<?php

namespace WechatMiniProgramSubscribeMessageBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;
use WechatMiniProgramSubscribeMessageBundle\Request\GetPrivateTemplateListRequest;
use WechatMiniProgramSubscribeMessageBundle\Service\TemplateDataValidator;

#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class SubscribeTemplateSyncService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Client $client,
        private readonly SubscribeTemplateRepository $subscribeTemplateRepository,
        private readonly SubscribeParamRepository $subscribeParamRepository,
        private readonly LoggerInterface $logger,
        private readonly TemplateDataValidator $validator,
    ) {
    }

    /**
     * 同步所有有效账号的订阅模板
     *
     * @return array{syncCount: int, errorCount: int, errors: array<int, array{account: string, error: string}>}
     */
    public function syncAllAccounts(): array
    {
        $syncCount = 0;
        $errorCount = 0;
        $errors = [];

        $accounts = $this->accountRepository->findBy(['valid' => true]);
        foreach ($accounts as $account) {
            try {
                $count = $this->syncTemplatesForAccount($account);
                $syncCount += $count;
            } catch (\Throwable $exception) {
                ++$errorCount;
                $errors[] = [
                    'account' => $account->getAppId(),
                    'error' => $exception->getMessage(),
                ];
                $this->logger->error("同步小程序订阅模板失败:[{$account->getAppId()}]", [
                    'exception' => $exception,
                    'account' => $account,
                ]);
            }
        }

        return [
            'syncCount' => $syncCount,
            'errorCount' => $errorCount,
            'errors' => $errors,
        ];
    }

    public function syncTemplatesForAccount(Account $account): int
    {
        $request = new GetPrivateTemplateListRequest();
        $request->setAccount($account);

        $response = $this->client->request($request);

        // 验证响应数据类型
        if (!is_array($response)) {
            $this->logger->warning('微信API返回的响应数据格式不正确', [
                'account' => $account->getAppId(),
                'response_type' => gettype($response),
            ]);
            return 0;
        }

        if (!isset($response['data']) || !is_array($response['data'])) {
            $this->logger->warning('微信API响应中缺少data字段或类型不正确', [
                'account' => $account->getAppId(),
                'response' => $response,
            ]);
            return 0;
        }

        $count = 0;
        foreach ($response['data'] as $datum) {
            $validDatum = $this->validator->validateTemplateDatum($datum);
            if ($validDatum !== false) {
                $this->processTemplateDatum($account, $validDatum);
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function processTemplateDatum(Account $account, array $datum): void
    {
        $template = $this->createOrUpdateTemplate($account, $datum);
        $this->processEnumValues($template, $datum);
        $this->processContentParams($template);
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function createOrUpdateTemplate(Account $account, array $datum): SubscribeTemplate
    {
        $this->validateTemplateDatum($datum);

        /** @var string $priTmplId */
        $priTmplId = $datum['priTmplId'];
        $template = $this->findOrCreateTemplate($account, $priTmplId);
        $this->updateTemplateFields($template, $datum);

        $this->entityManager->persist($template);
        $this->entityManager->flush();

        return $template;
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function validateTemplateDatum(array $datum): void
    {
        if (!isset($datum['priTmplId']) || !is_string($datum['priTmplId'])) {
            throw new \InvalidArgumentException('模板数据缺少有效的priTmplId字段');
        }
    }

    private function findOrCreateTemplate(Account $account, string $priTmplId): SubscribeTemplate
    {
        $template = $this->subscribeTemplateRepository->findOneBy([
            'account' => $account,
            'priTmplId' => $priTmplId,
        ]);

        if (null === $template) {
            $template = new SubscribeTemplate();
            $template->setAccount($account);
            $template->setPriTmplId($priTmplId);
        }

        return $template;
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function updateTemplateFields(SubscribeTemplate $template, array $datum): void
    {
        $this->setTemplateType($template, $datum);
        $this->setTemplateStringFields($template, $datum);
        $template->setValid(true);
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function setTemplateType(SubscribeTemplate $template, array $datum): void
    {
        if (isset($datum['type']) && (is_int($datum['type']) || is_string($datum['type']))) {
            $type = SubscribeTemplateType::tryFrom($datum['type']);
            if (null !== $type) {
                $template->setType($type);
            }
        }
    }

    /**
     * @param array<string, mixed> $datum
     */
    private function setTemplateStringFields(SubscribeTemplate $template, array $datum): void
    {
        if (isset($datum['title']) && is_string($datum['title'])) {
            $template->setTitle($datum['title']);
        }

        if (isset($datum['content'])) {
            $template->setContent(is_string($datum['content']) ? $datum['content'] : null);
        }

        if (isset($datum['example'])) {
            $template->setExample(is_string($datum['example']) ? $datum['example'] : null);
        }
    }


    /**
     * @param array<string, mixed> $datum
     */
    private function processEnumValues(SubscribeTemplate $template, array $datum): void
    {
        if (!$this->validator->hasValidEnumValueList($datum)) {
            return;
        }

        /** @var array<mixed> $enumValueList */
        $enumValueList = $datum['keywordEnumValueList'];

        foreach ($enumValueList as $enumDatum) {
            $validEnumDatum = $this->validator->validateEnumDatum($enumDatum);
            if ($validEnumDatum !== false) {
                $this->createOrUpdateEnumParam($template, $validEnumDatum, $datum);
            }
        }
    }


    /**
     * @param array<string, mixed> $enumDatum
     * @param array<string, mixed> $datum
     */
    private function createOrUpdateEnumParam(SubscribeTemplate $template, array $enumDatum, array $datum): void
    {
        // 验证keywordCode字段
        if (!isset($enumDatum['keywordCode']) || !is_string($enumDatum['keywordCode'])) {
            $this->logger->warning('枚举数据缺少有效的keywordCode字段', [
                'template' => $template->getId(),
                'enumDatum' => $enumDatum,
            ]);
            return;
        }

        $codeKey = str_replace('.DATA', '', $enumDatum['keywordCode']);
        $param = $this->subscribeParamRepository->findOneBy([
            'template' => $template,
            'code' => $codeKey,
        ]);

        if (null === $param) {
            $param = new SubscribeParam();
            $param->setTemplate($template);
            $param->setCode($codeKey);
            $param->setType(SubscribeTemplateData::ENUM);
        }

        // 验证enumValueList字段
        if (isset($datum['enumValueList']) && is_array($datum['enumValueList'])) {
            // 验证数组中的每个元素都是字符串
            $enumValues = [];
            foreach ($datum['enumValueList'] as $value) {
                if (is_string($value)) {
                    $enumValues[] = $value;
                }
            }
            $param->setEnumValues($enumValues);
        } else {
            $param->setEnumValues(null);
        }

        $this->entityManager->persist($param);
        $this->entityManager->flush();
        $this->entityManager->detach($param);
    }

    private function processContentParams(SubscribeTemplate $template): void
    {
        $content = $template->getContent();
        if (null === $content) {
            return;
        }
        preg_match_all('@{{(.*?).DATA}}@', $content, $matches);
        foreach ($matches[1] as $codeKey) {
            $this->createOrUpdateContentParam($template, $codeKey);
        }
    }

    private function createOrUpdateContentParam(SubscribeTemplate $template, string $codeKey): void
    {
        $param = $this->subscribeParamRepository->findOneBy([
            'template' => $template,
            'code' => $codeKey,
        ]);

        if (null === $param) {
            $param = new SubscribeParam();
            $param->setTemplate($template);
            $param->setCode($codeKey);

            preg_match('@(.*?)(\d+)@', $codeKey, $match);
            $type = SubscribeTemplateData::tryFrom($match[1]);
            if (null !== $type) {
                $param->setType($type);
            }
        }

        $this->entityManager->persist($param);
        $this->entityManager->flush();
        $this->entityManager->detach($param);
    }
}
