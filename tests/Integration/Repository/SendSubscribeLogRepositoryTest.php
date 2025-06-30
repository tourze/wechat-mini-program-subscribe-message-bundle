<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;
use WechatMiniProgramSubscribeMessageBundle\Repository\SendSubscribeLogRepository;

class SendSubscribeLogRepositoryTest extends TestCase
{
    public function testRepositoryConstruction()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);

        // Act
        $repository = new SendSubscribeLogRepository($registry);

        // Assert
        $this->assertInstanceOf(SendSubscribeLogRepository::class, $repository);
    }


    public function testRepositoryEntityClass()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new SendSubscribeLogRepository($registry);

        // Act & Assert
        $reflection = new \ReflectionClass($repository);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        // The parent constructor should be called with SendSubscribeLog::class
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
    }
}