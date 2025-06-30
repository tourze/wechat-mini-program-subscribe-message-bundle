<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;

class SubscribeMsgPopupEventTest extends TestCase
{
    public function testEventCreation()
    {
        // Act
        $event = new SubscribeMsgPopupEvent();

        // Assert
        $this->assertInstanceOf(SubscribeMsgPopupEvent::class, $event);
    }

    public function testSetAndGetLog()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $log = $this->createMock(SubscribeMessageLog::class);

        // Act
        $event->setLog($log);

        // Assert
        $this->assertSame($log, $event->getLog());
    }

    public function testSetAndGetAccount()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $account = $this->createMock(Account::class);

        // Act
        $event->setAccount($account);

        // Assert
        $this->assertSame($account, $event->getAccount());
    }

    public function testSetAndGetTemplateId()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $templateId = 'template-123';

        // Act
        $event->setTemplateId($templateId);

        // Assert
        $this->assertEquals($templateId, $event->getTemplateId());
    }

    public function testSetAndGetUser()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $user = $this->createMock(UserInterface::class);

        // Act
        $event->setUser($user);

        // Assert
        $this->assertSame($user, $event->getUser());
    }

    public function testSetAndGetSubscribeStatus()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $status = 'accept';

        // Act
        $event->setSubscribeStatus($status);

        // Assert
        $this->assertEquals($status, $event->getSubscribeStatus());
    }

    public function testSetSubscribeStatusWithNull()
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();

        // Act
        $event->setSubscribeStatus(null);

        // Assert
        $this->assertNull($event->getSubscribeStatus());
    }

}