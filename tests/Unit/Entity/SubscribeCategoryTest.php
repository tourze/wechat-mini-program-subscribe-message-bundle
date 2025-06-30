<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

class SubscribeCategoryTest extends TestCase
{
    public function testEntityCreation()
    {
        // Act
        $entity = new SubscribeCategory();

        // Assert
        $this->assertInstanceOf(SubscribeCategory::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getAccount());
        $this->assertNull($entity->getCategoryId());
        $this->assertNull($entity->getName());
    }

    public function testSetAndGetAccount()
    {
        // Arrange
        $entity = new SubscribeCategory();
        $account = $this->createMock(Account::class);

        // Act
        $result = $entity->setAccount($account);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($account, $entity->getAccount());
    }

    public function testSetAndGetCategoryId()
    {
        // Arrange
        $entity = new SubscribeCategory();
        $categoryId = 123;

        // Act
        $result = $entity->setCategoryId($categoryId);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($categoryId, $entity->getCategoryId());
    }

    public function testSetAndGetName()
    {
        // Arrange
        $entity = new SubscribeCategory();
        $name = 'Test Category';

        // Act
        $result = $entity->setName($name);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($name, $entity->getName());
    }

    public function testToString()
    {
        // Arrange
        $entity = new SubscribeCategory();

        // Act
        $result = $entity->__toString();

        // Assert
        $this->assertEquals('', $result);
    }

}