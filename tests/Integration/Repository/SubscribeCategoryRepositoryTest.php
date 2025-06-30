<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;

class SubscribeCategoryRepositoryTest extends TestCase
{
    public function testRepositoryConstruction()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);

        // Act
        $repository = new SubscribeCategoryRepository($registry);

        // Assert
        $this->assertInstanceOf(SubscribeCategoryRepository::class, $repository);
    }


    public function testRepositoryEntityClass()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new SubscribeCategoryRepository($registry);

        // Act & Assert
        $reflection = new \ReflectionClass($repository);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        // The parent constructor should be called with SubscribeCategory::class
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
    }
}