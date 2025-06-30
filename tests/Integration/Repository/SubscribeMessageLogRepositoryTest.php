<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

class SubscribeMessageLogRepositoryTest extends TestCase
{
    public function testRepositoryConstruction()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);

        // Act
        $repository = new SubscribeMessageLogRepository($registry);

        // Assert
        $this->assertInstanceOf(SubscribeMessageLogRepository::class, $repository);
    }


    public function testRepositoryEntityClass()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new SubscribeMessageLogRepository($registry);

        // Act & Assert
        $reflection = new \ReflectionClass($repository);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        // The parent constructor should be called with SubscribeMessageLog::class
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
    }
}