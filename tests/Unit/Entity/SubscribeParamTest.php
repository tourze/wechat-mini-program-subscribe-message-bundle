<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

class SubscribeParamTest extends TestCase
{
    public function testEntityCreation()
    {
        // Act
        $entity = new SubscribeParam();

        // Assert
        $this->assertInstanceOf(SubscribeParam::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getTemplate());
        $this->assertNull($entity->getType());
        $this->assertNull($entity->getCode());
        $this->assertNull($entity->getMapExpression());
        $this->assertEquals([], $entity->getEnumValues());
    }

    public function testSetAndGetTemplate()
    {
        // Arrange
        $entity = new SubscribeParam();
        $template = $this->createMock(SubscribeTemplate::class);

        // Act
        $result = $entity->setTemplate($template);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($template, $entity->getTemplate());
    }

    public function testSetAndGetType()
    {
        // Arrange
        $entity = new SubscribeParam();
        $type = SubscribeTemplateData::ENUM;

        // Act
        $result = $entity->setType($type);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($type, $entity->getType());
    }

    public function testSetAndGetCode()
    {
        // Arrange
        $entity = new SubscribeParam();
        $code = 'test-code';

        // Act
        $result = $entity->setCode($code);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($code, $entity->getCode());
    }

    public function testSetAndGetMapExpression()
    {
        // Arrange
        $entity = new SubscribeParam();
        $expression = 'user.name';

        // Act
        $result = $entity->setMapExpression($expression);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($expression, $entity->getMapExpression());
    }

    public function testSetAndGetEnumValues()
    {
        // Arrange
        $entity = new SubscribeParam();
        $enumValues = ['value1', 'value2', 'value3'];

        // Act
        $result = $entity->setEnumValues($enumValues);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($enumValues, $entity->getEnumValues());
    }


    public function testToStringWithNullId()
    {
        // Arrange
        $entity = new SubscribeParam();

        // Act
        $result = $entity->__toString();

        // Assert
        $this->assertEquals('', $result);
    }

    public function testToStringWithCode()
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
        $this->assertEquals($code, $result);
    }

}