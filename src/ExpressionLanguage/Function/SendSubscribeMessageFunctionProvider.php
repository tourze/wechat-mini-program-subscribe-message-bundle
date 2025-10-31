<?php

namespace WechatMiniProgramSubscribeMessageBundle\ExpressionLanguage\Function;

use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Enum\MiniProgramState;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;

/**
 * 方便运营在后台配置函数
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.send.html
 */
#[Autoconfigure(public: true)]
#[AutoconfigureTag(name: 'ecol.function.provider')]
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
readonly class SendSubscribeMessageFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private UserLoaderInterface $userLoader,
        private Client $client,
        private LoggerInterface $logger,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('sendWechatMiniProgramSubscribeMessage', fn (...$args) => sprintf('\%s(%s)', 'sendWechatMiniProgramSubscribeMessage', implode(', ', $args)), function ($values, ...$args) {
                $this->logger->debug('sendWechatMiniProgramSubscribeMessage', [
                    'values' => $values,
                    'args' => $args,
                ]);

                return $this->sendWechatMiniProgramSubscribeMessage($values, ...$args);
            }),

            new ExpressionFunction('发送微信小程序订阅消息', fn (...$args) => sprintf('\%s(%s)', '发送微信小程序订阅消息', implode(', ', $args)), function ($values, ...$args) {
                $this->logger->debug('sendWechatMiniProgramSubscribeMessage', [
                    'values' => $values,
                    'args' => $args,
                ]);

                return $this->sendWechatMiniProgramSubscribeMessage($values, ...$args);
            }),
        ];
    }

    /**
     * 发送微信小程序订阅消息，此处一般都是指的一次性消息
     *
     * @param array $values 这里代表的是执行时的上下文信息，具体可以看 \AppBundle\ExpressionLanguage\MessageListener
     *
     * @return bool 发送成功或失败
     *
     * @throws InvalidArgumentException
     */
    /**
     * @param array<string, mixed> $values
     * @param array<string, mixed>|string|null $data
     */
    public function sendWechatMiniProgramSubscribeMessage(array $values, UserInterface $user, string $templateId, array|string|null $data = null, ?string $page = null, ?string $miniprogramState = null): bool
    {
        // Check if user has a getPassword method (duck typing for PasswordAuthenticatedUserInterface)
        if (!method_exists($user, 'getPassword')) {
            return false;
        }

        $wechatUser = $this->loadWechatUser($user);
        if (null === $wechatUser) {
            return false;
        }

        $postData = $this->processMessageData($data, $values);
        $request = $this->buildSubscribeMessageRequest($wechatUser, $templateId, $postData, $page, $miniprogramState);

        return $this->sendRequest($request);
    }

    private function loadWechatUser(UserInterface $user): ?object
    {
        $wechatUser = $this->userLoader->loadUserByOpenId($user->getUserIdentifier());
        if (null === $wechatUser) {
            $wechatUser = $this->userLoader->loadUserByUnionId($user->getUserIdentifier());
        }

        if (null === $wechatUser) {
            $this->logger->warning('找不到指定用户的微信用户信息', [
                'user' => $user,
            ]);
        }

        return $wechatUser;
    }

    /**
     * @param array<string, mixed>|string|null $data
     * @param array<string, mixed> $values
     * @return array<string, array<string, string>>
     */
    private function processMessageData(array|string|null $data, array $values): array
    {
        $postData = [];

        if (!is_array($data)) {
            return $postData;
        }

        foreach ($data as $name => $datum) {
            $processedDatum = $this->processSingleDataItem($datum, $values);
            $formattedItem = $this->formatDataItem($processedDatum);
            $postData[(string) $name] = $this->ensureStringValues($formattedItem);
        }

        return $postData;
    }

    /**
     * @param array<string, mixed> $values
     */
    private function processSingleDataItem(mixed $datum, array $values): mixed
    {
        if (!is_string($datum)) {
            return $datum;
        }

        $tmp = explode(':', $datum, 2);
        if (count($tmp) < 2) {
            return $datum;
        }

        $newExp = $tmp[1];

        try {
            $l = new ExpressionLanguage();

            return $l->evaluate($newExp, $values);
        } catch (\Throwable $exception) {
            $this->logger->error('解析微信小程序data里面的数据项表达式时出错', [
                'expression' => $newExp,
                'data' => $datum,
                'exception' => $exception,
            ]);

            return $newExp;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function formatDataItem(mixed $datum): array
    {
        if (is_array($datum)) {
            // Ensure all keys are strings
            $result = [];
            foreach ($datum as $key => $value) {
                $result[(string) $key] = $value;
            }
            return $result;
        }

        return [
            'value' => $datum,
        ];
    }

    /**
     * 确保数组的所有值都是字符串类型，满足微信API要求
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    private function ensureStringValues(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = $this->convertToString($value);
        }
        return $result;
    }

    /**
     * 将任意类型的值转换为字符串
     */
    private function convertToString(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_array($value) || is_object($value)) {
            $encoded = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            return $encoded !== false ? $encoded : '';
        }

        return '';
    }

    /**
     * @param array<string, array<string, string>> $postData
     */
    private function buildSubscribeMessageRequest(object $wechatUser, string $templateId, array $postData, ?string $page, ?string $miniprogramState): SendSubscribeMessageRequest
    {
        $request = new SendSubscribeMessageRequest();
        // TODO: UserInterface 需要添加 getAccount() 方法
        // $request->setAccount($wechatUser->getAccount());
        $openId = method_exists($wechatUser, 'getOpenId') ? $this->convertToString($wechatUser->getOpenId()) : '';
        $request->setToUser($openId);
        $request->setTemplateId($templateId);
        $request->setData($postData);
        $request->setPage($page);
        $request->setMiniProgramState(null !== $miniprogramState ? MiniProgramState::tryFrom($miniprogramState) : null);

        return $request;
    }

    private function sendRequest(SendSubscribeMessageRequest $request): bool
    {
        try {
            $this->client->asyncRequest($request);

            return true;
        } catch (\Throwable $exception) {
            $this->logger->error('表达式发送订阅消息时发生异常', [
                'exception' => $exception,
                'request' => $request,
            ]);

            return false;
        }
    }
}
