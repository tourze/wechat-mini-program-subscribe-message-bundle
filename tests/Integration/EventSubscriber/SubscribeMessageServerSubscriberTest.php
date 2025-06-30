<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use WechatMiniProgramBundle\Entity\Account;
use Tourze\WechatMiniProgramUserContracts\UserInterface as WechatUserInterface;
use WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;
use WechatMiniProgramSubscribeMessageBundle\EventSubscriber\SubscribeMessageServerSubscriber;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

class SubscribeMessageServerSubscriberTest extends TestCase
{
    public function testOnPopupCallbackWithValidMessage()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $user = $this->createMock(WechatUserInterface::class);
        
        $message = [
            'Event' => 'subscribe_msg_popup_event',
            'List' => [
                'TemplateId' => 'template-123',
                'SubscribeStatusString' => 'accept',
                'PopupScene' => '0'
            ]
        ];

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getAccount')->willReturn($account);
        $event->method('getWechatUser')->willReturn($user);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->once())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->once())->method('dispatch');

        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onPopupCallback($event);

        // Assert - expectations verified by mock framework
    }

    public function testOnPopupCallbackWithWrongEvent()
    {
        // Arrange
        $message = ['Event' => 'other_event'];
        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->never())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onPopupCallback($event);

        // Assert - expectations verified by mock framework
    }

    public function testOnPopupCallbackWithMissingTemplateId()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $user = $this->createMock(WechatUserInterface::class);
        
        $message = [
            'Event' => 'subscribe_msg_popup_event',
            'List' => [
                'TemplateId' => '', // 空的模板ID以触发错误日志
                'SubscribeStatusString' => 'accept',
                'PopupScene' => '0'
            ]
        ];

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getAccount')->willReturn($account);
        $event->method('getWechatUser')->willReturn($user);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->never())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onPopupCallback($event);

        // Assert - expectations verified by mock framework
    }

    public function testOnManageCallbackWithValidMessage()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $user = $this->createMock(WechatUserInterface::class);
        
        $message = [
            'Event' => 'subscribe_msg_change_event',
            'SubscribeMsgChangeEvent' => [
                'List' => [
                    'TemplateId' => 'template-123',
                    'SubscribeStatusString' => 'reject'
                ]
            ]
        ];

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getAccount')->willReturn($account);
        $event->method('getWechatUser')->willReturn($user);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->once())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onManageCallback($event);

        // Assert - expectations verified by mock framework
    }

    public function testOnResultCallbackWithExistingLog()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $user = $this->createMock(WechatUserInterface::class);
        $existingLog = $this->createMock(SubscribeMessageLog::class);
        
        $message = [
            'Event' => 'subscribe_msg_sent_event',
            'SubscribeMsgSentEvent' => [
                'List' => [
                    'TemplateId' => 'template-123',
                    'MsgID' => 12345,
                    'ErrorCode' => 0,
                    'ErrorStatus' => 'success'
                ]
            ]
        ];

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getAccount')->willReturn($account);
        $event->method('getWechatUser')->willReturn($user);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $messageLogRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingLog);

        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->once())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onResultCallback($event);

        // Assert - expectations verified by mock framework
    }

    public function testOnResultCallbackWithoutExistingLog()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $user = $this->createMock(WechatUserInterface::class);
        
        $message = [
            'Event' => 'subscribe_msg_sent_event',
            'SubscribeMsgSentEvent' => [
                'List' => [
                    'TemplateId' => 'template-123',
                    'MsgID' => 12345,
                    'ErrorCode' => 0,
                    'ErrorStatus' => 'success'
                ]
            ]
        ];

        $event = $this->createMock(ServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getAccount')->willReturn($account);
        $event->method('getWechatUser')->willReturn($user);

        $messageLogRepository = $this->createMock(SubscribeMessageLogRepository::class);
        $messageLogRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $asyncInsertService = $this->createMock(AsyncInsertService::class);
        $asyncInsertService->expects($this->once())->method('asyncInsert');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $subscriber = new SubscribeMessageServerSubscriber(
            $messageLogRepository,
            $asyncInsertService,
            $eventDispatcher,
            $logger
        );

        // Act
        $subscriber->onResultCallback($event);

        // Assert - expectations verified by mock framework
    }
}