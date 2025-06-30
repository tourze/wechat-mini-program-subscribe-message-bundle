<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

class SendSubscribeLogTest extends TestCase
{
    public function testEntityCreation()
    {
        // Act
        $entity = new SendSubscribeLog();

        // Assert
        $this->assertInstanceOf(SendSubscribeLog::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getTemplateId());
        $this->assertNull($entity->getUser());
        $this->assertNull($entity->getAccount());
        $this->assertNull($entity->getResult());
        $this->assertNull($entity->getRemark());
    }

    public function testSetAndGetTemplateId()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $templateId = 'template-123';

        // Act
        $result = $entity->setTemplateId($templateId);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($templateId, $entity->getTemplateId());
    }

    public function testSetAndGetUser()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $user = $this->createMock(UserInterface::class);

        // Act
        $result = $entity->setUser($user);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($user, $entity->getUser());
    }

    public function testSetAndGetAccount()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $account = $this->createMock(Account::class);

        // Act
        $result = $entity->setAccount($account);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($account, $entity->getAccount());
    }

    public function testSetAndGetResult()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $result = 'success';

        // Act
        $entity->setResult($result);

        // Assert
        $this->assertEquals($result, $entity->getResult());
    }

    public function testSetAndGetRemark()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $remark = 'Test remark';

        // Act
        $result = $entity->setRemark($remark);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($remark, $entity->getRemark());
    }

    public function testSetAndGetCreatedFromIp()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $ip = '192.168.1.1';

        // Act
        $result = $entity->setCreatedFromIp($ip);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($ip, $entity->getCreatedFromIp());
    }

    public function testSetAndGetUpdatedFromIp()
    {
        // Arrange
        $entity = new SendSubscribeLog();
        $ip = '192.168.1.2';

        // Act
        $result = $entity->setUpdatedFromIp($ip);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($ip, $entity->getUpdatedFromIp());
    }

    public function testToString()
    {
        // Arrange
        $entity = new SendSubscribeLog();

        // Act
        $result = $entity->__toString();

        // Assert
        $this->assertEquals('', $result);
    }

}