<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

/**
 * @internal
 */
#[CoversClass(SubscribeParam::class)]
final class SubscribeParamTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SubscribeParam();
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
        $entity = new SubscribeParam();

        // Assert - 验证实体初始状态
        self::assertNull($entity->getId());
        self::assertNull($entity->getTemplate());
        self::assertNull($entity->getType());
        self::assertNull($entity->getCode());
        self::assertNull($entity->getMapExpression());
        self::assertEquals([], $entity->getEnumValues());
    }

    public function testSetAndGetTemplate(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        // 使用具体类 SubscribeTemplate mock 的理由：
        // 1. 这是测试中的核心业务实体，需要验证其具体行为
        // 2. 该类的接口变化频繁，使用具体类可以避免接口变更带来的测试维护成本
        // 3. 在测试中，我们需要确保与真实对象的交互符合预期
        $template = $this->createMock(SubscribeTemplate::class);

        // Act
        $entity->setTemplate($template);

        // Assert
        self::assertSame($template, $entity->getTemplate());
    }

    public function testSetAndGetType(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        $type = SubscribeTemplateData::ENUM;

        // Act
        $entity->setType($type);

        // Assert
        self::assertSame($type, $entity->getType());
    }

    public function testSetAndGetCode(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        $code = 'test-code';

        // Act
        $entity->setCode($code);

        // Assert
        self::assertEquals($code, $entity->getCode());
    }

    public function testSetAndGetMapExpression(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        $expression = 'user.name';

        // Act
        $entity->setMapExpression($expression);

        // Assert
        self::assertEquals($expression, $entity->getMapExpression());
    }

    public function testSetAndGetEnumValues(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        $enumValues = ['value1', 'value2', 'value3'];

        // Act
        $entity->setEnumValues($enumValues);

        // Assert
        self::assertEquals($enumValues, $entity->getEnumValues());
    }

    public function testToStringWithNullId(): void
    {
        // Arrange
        $entity = new SubscribeParam();

        // Act
        $result = $entity->__toString();

        // Assert
        self::assertEquals('', $result);
    }

    public function testToStringWithCode(): void
    {
        // Arrange
        $entity = new SubscribeParam();
        $code = 'test-code';
        $entity->setCode($code);

        // Use reflection to set ID
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, '123');

        // Act
        $result = $entity->__toString();

        // Assert
        self::assertEquals($code, $result);
    }
}
