<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit\ExpressionLanguage\Function;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface as WechatUserInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\ExpressionLanguage\Function\SendSubscribeMessageFunctionProvider;

class SendSubscribeMessageFunctionProviderTest extends TestCase
{
    public function testGetFunctions()
    {
        // Arrange
        $userLoader = $this->createMock(UserLoaderInterface::class);
        $client = $this->createMock(Client::class);
        $logger = $this->createMock(LoggerInterface::class);

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $functions = $provider->getFunctions();

        // Assert
        $this->assertCount(2, $functions);
        $this->assertEquals('sendWechatMiniProgramSubscribeMessage', $functions[0]->getName());
        $this->assertEquals('发送微信小程序订阅消息', $functions[1]->getName());
    }

    public function testSendWechatMiniProgramSubscribeMessageWithValidUser()
    {
        // Arrange
        $user = new class implements UserInterface, PasswordAuthenticatedUserInterface {
            public function getUserIdentifier(): string { return 'test-openid'; }
            public function eraseCredentials(): void {}
            public function getPassword(): ?string { return null; }
            public function getRoles(): array { return []; }
        };

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->with('test-openid')
            ->willReturn($wechatUser);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('asyncRequest');

        $logger = $this->createMock(LoggerInterface::class);

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            ['context' => 'test'],
            $user,
            'template-123',
            ['key1' => 'value1'],
            '/pages/index/index',
            'formal'
        );

        // Assert
        $this->assertTrue($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithInvalidUser()
    {
        // Arrange
        $user = new class implements UserInterface {
            public function getUserIdentifier(): string { return 'test'; }
            public function eraseCredentials(): void {}
            public function getRoles(): array { return []; }
        };

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $client = $this->createMock(Client::class);
        $logger = $this->createMock(LoggerInterface::class);

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            [],
            $user,
            'template-123',
            [],
            null,
            'formal'
        );

        // Assert
        $this->assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithMissingWechatUser()
    {
        // Arrange
        $user = new class implements UserInterface, PasswordAuthenticatedUserInterface {
            public function getUserIdentifier(): string { return 'test-openid'; }
            public function eraseCredentials(): void {}
            public function getPassword(): ?string { return null; }
            public function getRoles(): array { return []; }
        };

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn(null);
        $userLoader->expects($this->once())
            ->method('loadUserByUnionId')
            ->willReturn(null);

        $client = $this->createMock(Client::class);
        $client->expects($this->never())->method('asyncRequest');

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            [],
            $user,
            'template-123',
            [],
            null,
            'formal'
        );

        // Assert
        $this->assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithException()
    {
        // Arrange
        $user = new class implements UserInterface, PasswordAuthenticatedUserInterface {
            public function getUserIdentifier(): string { return 'test-openid'; }
            public function eraseCredentials(): void {}
            public function getPassword(): ?string { return null; }
            public function getRoles(): array { return []; }
        };

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn($wechatUser);

        $exception = new \Exception('Client error');
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('asyncRequest')
            ->willThrowException($exception);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            [],
            $user,
            'template-123',
            [],
            null,
            'formal'
        );

        // Assert
        $this->assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithStringData()
    {
        // Arrange
        $user = new class implements UserInterface, PasswordAuthenticatedUserInterface {
            public function getUserIdentifier(): string { return 'test-openid'; }
            public function eraseCredentials(): void {}
            public function getPassword(): ?string { return null; }
            public function getRoles(): array { return []; }
        };

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn($wechatUser);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('asyncRequest');

        $logger = $this->createMock(LoggerInterface::class);

        $provider = new SendSubscribeMessageFunctionProvider($userLoader, $client, $logger);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            ['time' => time()],
            $user,
            'template-123',
            ['time01' => 'time:time']
        );

        // Assert
        $this->assertTrue($result);
    }
}