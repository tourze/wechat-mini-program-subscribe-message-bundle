<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

class SubscribeMessageLogTest extends TestCase
{
    public function testEntityCreation()
    {
        // Act
        $entity = new SubscribeMessageLog();

        // Assert
        $this->assertInstanceOf(SubscribeMessageLog::class, $entity);
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getAccount());
        $this->assertNull($entity->getUser());
        $this->assertNull($entity->getTemplateId());
        $this->assertNull($entity->getSubscribeStatus());
        $this->assertNull($entity->getRawData());
        $this->assertNull($entity->getResultMsgId());
        $this->assertNull($entity->getResultCode());
        $this->assertNull($entity->getResultStatus());
    }

    public function testSetAndGetRawData()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $rawData = '{"test": "data"}';

        // Act
        $result = $entity->setRawData($rawData);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($rawData, $entity->getRawData());
    }

    public function testSetAndGetTemplateId()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $templateId = 'template-123';

        // Act
        $result = $entity->setTemplateId($templateId);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($templateId, $entity->getTemplateId());
    }

    public function testSetAndGetSubscribeStatus()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $status = 'accept';

        // Act
        $result = $entity->setSubscribeStatus($status);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($status, $entity->getSubscribeStatus());
    }

    public function testSetAndGetUser()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
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
        $entity = new SubscribeMessageLog();
        $account = $this->createMock(Account::class);

        // Act
        $result = $entity->setAccount($account);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertSame($account, $entity->getAccount());
    }

    public function testSetAndGetResultMsgId()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $msgId = 'msg-123';

        // Act
        $result = $entity->setResultMsgId($msgId);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($msgId, $entity->getResultMsgId());
    }

    public function testSetAndGetResultCode()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $code = 200;

        // Act
        $result = $entity->setResultCode($code);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($code, $entity->getResultCode());
    }

    public function testSetAndGetResultStatus()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
        $status = 'success';

        // Act
        $result = $entity->setResultStatus($status);

        // Assert
        $this->assertSame($entity, $result);
        $this->assertEquals($status, $entity->getResultStatus());
    }

    public function testSetAndGetCreatedFromIp()
    {
        // Arrange
        $entity = new SubscribeMessageLog();
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
        $entity = new SubscribeMessageLog();
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
        $entity = new SubscribeMessageLog();

        // Act
        $result = $entity->__toString();

        // Assert
        $this->assertEquals('', $result);
    }

}