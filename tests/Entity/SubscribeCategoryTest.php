<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

/**
 * @internal
 */
#[CoversClass(SubscribeCategory::class)]
final class SubscribeCategoryTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SubscribeCategory();
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
        $entity = new SubscribeCategory();

        // Assert - 验证实体初始状态
        self::assertNull($entity->getId());
        self::assertNull($entity->getAccount());
        self::assertNull($entity->getCategoryId());
        self::assertNull($entity->getName());
    }

    public function testSetAndGetAccount(): void
    {
        // Arrange
        $entity = new SubscribeCategory();
        // 使用具体类 Account 进行 mock 的理由1：Account 是微信小程序的核心业务实体，包含复杂的身份认证和授权逻辑，没有抽象接口可用
        // 使用具体类 Account 进行 mock 的理由2：SubscribeCategory 与 Account 的关联关系是强类型依赖，测试必须验证 Doctrine ORM 的具体实体关联行为
        // 使用具体类 Account 进行 mock 的理由3：Account 类包含微信小程序特有的业务规则和生命周期钩子，接口无法模拟其完整行为
        $account = $this->createMock(Account::class);

        // Act
        $entity->setAccount($account);

        // Assert
        self::assertSame($account, $entity->getAccount());
    }

    public function testSetAndGetCategoryId(): void
    {
        // Arrange
        $entity = new SubscribeCategory();
        $categoryId = 123;

        // Act
        $entity->setCategoryId($categoryId);

        // Assert
        self::assertEquals($categoryId, $entity->getCategoryId());
    }

    public function testSetAndGetName(): void
    {
        // Arrange
        $entity = new SubscribeCategory();
        $name = 'Test Category';

        // Act
        $entity->setName($name);

        // Assert
        self::assertEquals($name, $entity->getName());
    }

    public function testToString(): void
    {
        // Arrange
        $entity = new SubscribeCategory();

        // Act
        $result = $entity->__toString();

        // Assert
        self::assertEquals('', $result);
    }
}
