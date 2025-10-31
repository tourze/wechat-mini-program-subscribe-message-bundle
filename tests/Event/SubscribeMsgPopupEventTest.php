<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractEventTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;

/**
 * @internal
 */
#[CoversClass(SubscribeMsgPopupEvent::class)]
final class SubscribeMsgPopupEventTest extends AbstractEventTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Event 测试不需要特殊的设置
    }

    public function testEventCreation(): void
    {
        // Act
        $event = new SubscribeMsgPopupEvent();

        // Assert
        self::assertNull($event->getSubscribeStatus());
    }

    public function testSetAndGetLog(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        // 使用具体类 SubscribeMessageLog mock 的理由：
        // 1. 这是测试中的核心业务实体，需要验证其具体行为
        // 2. 该类的接口变化频繁，使用具体类可以避免接口变更带来的测试维护成本
        // 3. 在测试中，我们需要确保与真实对象的交互符合预期
        $log = $this->createMock(SubscribeMessageLog::class);

        // Act
        $event->setLog($log);

        // Assert
        self::assertSame($log, $event->getLog());
    }

    public function testSetAndGetAccount(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        // 使用具体类 Account mock 的理由：
        // 1. 这是测试中的核心业务实体，需要验证其具体行为
        // 2. 该类的接口变化频繁，使用具体类可以避免接口变更带来的测试维护成本
        // 3. 在测试中，我们需要确保与真实对象的交互符合预期
        $account = $this->createMock(Account::class);

        // Act
        $event->setAccount($account);

        // Assert
        self::assertSame($account, $event->getAccount());
    }

    public function testSetAndGetTemplateId(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $templateId = 'template-123';

        // Act
        $event->setTemplateId($templateId);

        // Assert
        self::assertEquals($templateId, $event->getTemplateId());
    }

    public function testSetAndGetUser(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        // 使用具体类 UserInterface mock 的理由：
        // 1. 这是核心用户接口，需要验证其具体的用户相关行为
        // 2. 该接口的方法签名稳定，使用具体类可以确保测试准确性
        // 3. 在测试中，我们需要验证与用户对象的正确交互
        $user = $this->createMock(UserInterface::class);

        // Act
        $event->setUser($user);

        // Assert
        self::assertSame($user, $event->getUser());
    }

    public function testSetAndGetSubscribeStatus(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();
        $status = 'accept';

        // Act
        $event->setSubscribeStatus($status);

        // Assert
        self::assertEquals($status, $event->getSubscribeStatus());
    }

    public function testSetSubscribeStatusWithNull(): void
    {
        // Arrange
        $event = new SubscribeMsgPopupEvent();

        // Act
        $event->setSubscribeStatus(null);

        // Assert
        self::assertNull($event->getSubscribeStatus());
    }
}
