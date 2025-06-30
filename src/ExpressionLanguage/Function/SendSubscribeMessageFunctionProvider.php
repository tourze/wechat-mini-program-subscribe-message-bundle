<?php

namespace WechatMiniProgramSubscribeMessageBundle\ExpressionLanguage\Function;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
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
#[AutoconfigureTag(name: 'ecol.function.provider')]
class SendSubscribeMessageFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        private readonly UserLoaderInterface $userLoader,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
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
     * @return bool 发送成功或失败
     * @throws InvalidArgumentException
     */
    public function sendWechatMiniProgramSubscribeMessage(array $values, UserInterface $user, string $templateId, array|string|null $data = null, ?string $page = null, ?string $miniprogramState = null): bool
    {
        if (!($user instanceof PasswordAuthenticatedUserInterface)) {
            return false;
        }

        $wechatUser = $this->userLoader->loadUserByOpenId($user->getUserIdentifier());
        if ($wechatUser === null) {
            $wechatUser = $this->userLoader->loadUserByUnionId($user->getUserIdentifier());
        }

        if ($wechatUser === null) {
            $this->logger->warning('找不到指定用户的微信用户信息', [
                'user' => $user,
            ]);

            return false;
        }

        // 如果是字符串的话，我们需要额外处理
        // data的格式不一定标准，我们需要在这里处理一次
        $postData = [];
        foreach ($data as $name => $datum) {
            // 传入的格式，可能是 "number01:now.time" 这样子的
            if ((bool) is_string($datum)) {
                $tmp = explode(':', $datum, 2);
                $name = array_shift($tmp);
                $newExp = array_shift($tmp);

                // TODO 下面这里可以优化一下的
                try {
                    $l = new ExpressionLanguage();
                    $datum = $l->evaluate($newExp, $values);
                } catch (\Throwable $exception) {
                    $this->logger->error('解析微信小程序data里面的数据项表达式时出错', [
                        'expression' => $newExp,
                        'data' => $data,
                        'exception' => $exception,
                    ]);
                    $datum = $newExp;
                }
            }

            if (!is_array($datum)) {
                $postData[$name] = [
                    'value' => $datum,
                ];
            } else {
                $postData[$name] = $datum;
            }
        }

        // TODO page参数，我们也支持一下占位符替换吧，例如 /pages/index/index?id={id}
        // TODO 这里生成的path，自动加一层监测参数？

        $request = new SendSubscribeMessageRequest();
        // TODO: UserInterface 需要添加 getAccount() 方法
        // $request->setAccount($wechatUser->getAccount());
        $request->setToUser($wechatUser->getOpenId());
        $request->setTemplateId($templateId);
        $request->setData($postData);
        $request->setPage($page);
        $request->setMiniProgramState($miniprogramState !== null ? MiniProgramState::tryFrom($miniprogramState) : null);

        try {
            $this->client->asyncRequest($request);
        } catch (\Throwable $exception) {
            $this->logger->error('表达式发送订阅消息时发生异常', [
                'exception' => $exception,
                'request' => $request,
            ]);

            return false;
        }

        return true;
    }
}
