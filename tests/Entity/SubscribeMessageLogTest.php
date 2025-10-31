<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

/**
 * @internal
 */
#[CoversClass(SubscribeMessageLog::class)]
final class SubscribeMessageLogTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SubscribeMessageLog();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', 'test-id-123'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'updateTime' => ['updateTime', new \DateTimeImmutable()],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testEntityCreation(): void
    {
        // Act
        $entity = new SubscribeMessageLog();

        // Assert - 验证实体初始状态
        self::assertNull($entity->getId());
        self::assertNull($entity->getAccount());
        self::assertNull($entity->getUser());
        self::assertNull($entity->getTemplateId());
        self::assertNull($entity->getSubscribeStatus());
        self::assertNull($entity->getRawData());
        self::assertNull($entity->getResultMsgId());
        self::assertNull($entity->getResultCode());
        self::assertNull($entity->getResultStatus());
    }

    public function testSetAndGetRawData(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $rawData = '{"test": "data"}';

        // Act
        $entity->setRawData($rawData);

        // Assert
        self::assertEquals($rawData, $entity->getRawData());
    }

    public function testSetAndGetTemplateId(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $templateId = 'template-123';

        // Act
        $entity->setTemplateId($templateId);

        // Assert
        self::assertEquals($templateId, $entity->getTemplateId());
    }

    public function testSetAndGetSubscribeStatus(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $status = 'accept';

        // Act
        $entity->setSubscribeStatus($status);

        // Assert
        self::assertEquals($status, $entity->getSubscribeStatus());
    }

    public function testSetAndGetUser(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $user = $this->createMock(UserInterface::class);

        // Act
        $entity->setUser($user);

        // Assert
        self::assertSame($user, $entity->getUser());
    }

    public function testSetAndGetAccount(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        // 使用具体类 Account mock 的理由：
        // 1. 这是测试中的核心业务实体，需要验证其具体行为
        // 2. 该类的接口变化频繁，使用具体类可以避免接口变更带来的测试维护成本
        // 3. 在测试中，我们需要确保与真实对象的交互符合预期
        $account = $this->createMock(Account::class);

        // Act
        $entity->setAccount($account);

        // Assert
        self::assertSame($account, $entity->getAccount());
    }

    public function testSetAndGetResultMsgId(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $msgId = 'msg-123';

        // Act
        $entity->setResultMsgId($msgId);

        // Assert
        self::assertEquals($msgId, $entity->getResultMsgId());
    }

    public function testSetAndGetResultCode(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $code = 200;

        // Act
        $entity->setResultCode($code);

        // Assert
        self::assertEquals($code, $entity->getResultCode());
    }

    public function testSetAndGetResultStatus(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $status = 'success';

        // Act
        $entity->setResultStatus($status);

        // Assert
        self::assertEquals($status, $entity->getResultStatus());
    }

    public function testSetAndGetCreatedFromIp(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $ip = '192.168.1.1';

        // Act
        $entity->setCreatedFromIp($ip);

        // Assert
        self::assertEquals($ip, $entity->getCreatedFromIp());
    }

    public function testSetAndGetUpdatedFromIp(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $ip = '192.168.1.2';

        // Act
        $entity->setUpdatedFromIp($ip);

        // Assert
        self::assertEquals($ip, $entity->getUpdatedFromIp());
    }

    public function testToString(): void
    {
        // Arrange
        $entity = new SubscribeMessageLog();

        // Act
        $result = $entity->__toString();

        // Assert
        self::assertEquals('', $result);
    }
}
