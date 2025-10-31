<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

/**
 * @internal
 */
#[CoversClass(SendSubscribeLog::class)]
final class SendSubscribeLogTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SendSubscribeLog();
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
        $entity = new SendSubscribeLog();

        // Assert - 验证实体初始状态
        self::assertNull($entity->getId());
        self::assertNull($entity->getTemplateId());
        self::assertNull($entity->getUser());
        self::assertNull($entity->getAccount());
        self::assertNull($entity->getResult());
        self::assertNull($entity->getRemark());
    }

    public function testSetAndGetTemplateId(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $templateId = 'template-123';

        // Act
        $entity->setTemplateId($templateId);

        // Assert
        self::assertEquals($templateId, $entity->getTemplateId());
    }

    public function testSetAndGetUser(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        // 使用具体类 UserInterface mock 的理由：
        // 1. 这是核心用户接口，需要验证其具体的用户相关行为
        // 2. 该接口的方法签名稳定，使用具体类可以确保测试准确性
        // 3. 在测试中，我们需要验证与用户对象的正确交互
        $user = $this->createMock(UserInterface::class);

        // Act
        $entity->setUser($user);

        // Assert
        self::assertSame($user, $entity->getUser());
    }

    public function testSetAndGetAccount(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
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

    public function testSetAndGetResult(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $result = 'success';

        // Act
        $entity->setResult($result);

        // Assert
        self::assertEquals($result, $entity->getResult());
    }

    public function testSetAndGetRemark(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $remark = 'Test remark';

        // Act
        $entity->setRemark($remark);

        // Assert
        self::assertEquals($remark, $entity->getRemark());
    }

    public function testSetAndGetCreatedFromIp(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $ip = '192.168.1.1';

        // Act
        $entity->setCreatedFromIp($ip);

        // Assert
        self::assertEquals($ip, $entity->getCreatedFromIp());
    }

    public function testSetAndGetUpdatedFromIp(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $ip = '192.168.1.2';

        // Act
        $entity->setUpdatedFromIp($ip);

        // Assert
        self::assertEquals($ip, $entity->getUpdatedFromIp());
    }

    public function testToString(): void
    {
        // Arrange
        $entity = new SendSubscribeLog();

        // Act
        $result = $entity->__toString();

        // Assert
        self::assertEquals('', $result);
    }
}
