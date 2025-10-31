<?php

namespace WechatMiniProgramSubscribeMessageBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use HttpClientBundle\Event\AfterAsyncHttpClientEvent;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class WechatSubscribeEventSubscriber
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLoaderInterface $userLoader,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 记录订阅消息异步发送的情况
     */
    #[AsEventListener]
    public function afterAsyncHttpRequest(AfterAsyncHttpClientEvent $event): void
    {
        // 不是发送订阅消息的不处理
        $params = $event->getParams();
        if (!isset($params['options']) || !is_array($params['options']) || !isset($params['options']['body'])) {
            return;
        }

        $body = $params['options']['body'];
        if (!is_string($body)) {
            $this->logger->error('HTTP请求body不是字符串格式', ['body' => $body]);

            return;
        }

        $options = json_decode($body, true);
        if (!is_array($options)) {
            $this->logger->error('无法解析HTTP请求body为JSON数组', ['body' => $body]);

            return;
        }
        $this->logger->debug('AfterAsyncHttpClient', [
            'params' => $options,
        ]);

        // 不是发送订阅消息的不处理
        if (!isset($options['touser']) || !isset($options['template_id'])) {
            return;
        }

        $touser = $options['touser'];
        if (!is_string($touser)) {
            $this->logger->error('touser不是字符串格式', ['touser' => $touser]);

            return;
        }

        $templateId = $options['template_id'];
        if (!is_string($templateId)) {
            $this->logger->error('template_id不是字符串格式', ['template_id' => $templateId]);

            return;
        }

        $user = $this->userLoader->loadUserByOpenId($touser);
        if (null === $user) {
            return;
        }

        $sendLog = new SendSubscribeLog();
        $sendLog->setUser($user);
        // TODO: UserInterface 需要添加 getAccount() 方法
        // $sendLog->setAccount($user->getAccount());
        $sendLog->setTemplateId($templateId);
        $sendLog->setResult($event->getResult());
        $this->entityManager->persist($sendLog);
        $this->entityManager->flush();
    }
}
