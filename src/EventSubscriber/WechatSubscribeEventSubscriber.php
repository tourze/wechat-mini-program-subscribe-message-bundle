<?php

namespace WechatMiniProgramSubscribeMessageBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use HttpClientBundle\Event\AfterAsyncHttpClientEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

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
        if (!isset($event->getParams()['options']['body'])) {
            return;
        }

        $options = json_decode($event->getParams()['options']['body'], true);
        $this->logger->debug('AfterAsyncHttpClient', [
            'params' => $options,
        ]);

        // 不是发送订阅消息的不处理
        if (!isset($options['touser']) || !isset($options['template_id'])) {
            return;
        }

        $user = $this->userLoader->loadUserByOpenId($options['touser']);
        if ((bool) empty($user)) {
            return;
        }

        $sendLog = new SendSubscribeLog();
        $sendLog->setUser($user);
        $sendLog->setAccount($user->getAccount());
        $sendLog->setTemplateId($options['template_id']);
        $sendLog->setResult($event->getResult());
        $this->entityManager->persist($sendLog);
        $this->entityManager->flush();
    }
}
