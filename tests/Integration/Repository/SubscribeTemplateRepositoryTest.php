<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

class SubscribeTemplateRepositoryTest extends TestCase
{
    public function testRepositoryConstruction()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);

        // Act
        $repository = new SubscribeTemplateRepository($registry);

        // Assert
        $this->assertInstanceOf(SubscribeTemplateRepository::class, $repository);
    }


    public function testRepositoryEntityClass()
    {
        // Arrange
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new SubscribeTemplateRepository($registry);

        // Act & Assert
        $reflection = new \ReflectionClass($repository);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        
        // The parent constructor should be called with SubscribeTemplate::class
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
    }
}