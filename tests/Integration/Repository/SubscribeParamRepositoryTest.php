<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;

class SubscribeParamRepositoryTest extends TestCase
{
    public function testRepositoryConstruction()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);

        // Act
        $repository = new SubscribeParamRepository($registry);

        // Assert
        $this->assertInstanceOf(SubscribeParamRepository::class, $repository);
    }


    public function testRepositoryEntityClass()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new SubscribeParamRepository($registry);

        // Act & Assert
        $reflection = new \ReflectionClass($repository);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        // The parent constructor should be called with SubscribeParam::class
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
    }
}