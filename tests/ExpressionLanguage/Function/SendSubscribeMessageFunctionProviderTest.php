<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\ExpressionLanguage\Function;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\WechatMiniProgramUserContracts\UserInterface as WechatUserInterface;
use Tourze\WechatMiniProgramUserContracts\UserLoaderInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\ExpressionLanguage\Function\SendSubscribeMessageFunctionProvider;

/**
 * @internal
 */
#[CoversClass(SendSubscribeMessageFunctionProvider::class)]
#[RunTestsInSeparateProcesses]
final class SendSubscribeMessageFunctionProviderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testGetFunctions(): void
    {
        // Arrange
        $userLoader = $this->createMock(UserLoaderInterface::class);
        $client = $this->createMock(Client::class);

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

        // Act
        $functions = $provider->getFunctions();

        // Assert
        self::assertCount(2, $functions);
        self::assertEquals('sendWechatMiniProgramSubscribeMessage', $functions[0]->getName());
        self::assertEquals('发送微信小程序订阅消息', $functions[1]->getName());
    }

    public function testSendWechatMiniProgramSubscribeMessageWithValidUser(): void
    {
        // Arrange
        $user = $this->createNormalUser('test-openid');

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->with('test-openid')
            ->willReturn($wechatUser)
        ;

        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('asyncRequest');

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

        // Debug: 验证依赖注入
        $reflection = new \ReflectionClass($provider);
        $loaderProp = $reflection->getProperty('userLoader');
        $loaderProp->setAccessible(true);
        $actualLoader = $loaderProp->getValue($provider);
        var_dump('Loader class: ' . get_class($actualLoader));
        var_dump('Is mock: ' . ($actualLoader === $userLoader ? 'YES' : 'NO'));

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            ['context' => 'test'],
            $user,
            'template-123',
            ['key1' => 'value1'],
            '/pages/index/index',
            'formal'
        );

        var_dump('Result: ' . ($result ? 'TRUE' : 'FALSE'));

        // Assert
        self::assertTrue($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithInvalidUser(): void
    {
        // Arrange
        $user = $this->createNormalUser('test');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $client = $this->createMock(Client::class);

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

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
        self::assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithMissingWechatUser(): void
    {
        // Arrange
        $user = $this->createNormalUser('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn(null)
        ;
        $userLoader->expects($this->once())
            ->method('loadUserByUnionId')
            ->willReturn(null)
        ;

        $client = $this->createMock(Client::class);
        $client->expects($this->never())->method('asyncRequest');

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

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
        self::assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithException(): void
    {
        // Arrange
        $user = $this->createNormalUser('test-openid');

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn($wechatUser)
        ;

        $exception = new \Exception('Client error');
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('asyncRequest')
            ->willThrowException($exception)
        ;

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

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
        self::assertFalse($result);
    }

    public function testSendWechatMiniProgramSubscribeMessageWithStringData(): void
    {
        // Arrange
        $user = $this->createNormalUser('test-openid');

        $wechatUser = $this->createMock(WechatUserInterface::class);
        $wechatUser->method('getOpenId')->willReturn('test-openid');

        $userLoader = $this->createMock(UserLoaderInterface::class);
        $userLoader->expects($this->once())
            ->method('loadUserByOpenId')
            ->willReturn($wechatUser)
        ;

        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('asyncRequest');

        self::getContainer()->set(UserLoaderInterface::class, $userLoader);
        self::getContainer()->set(Client::class, $client);

        $provider = self::getService(SendSubscribeMessageFunctionProvider::class);

        // Act
        $result = $provider->sendWechatMiniProgramSubscribeMessage(
            ['time' => time()],
            $user,
            'template-123',
            ['time01' => 'time:time']
        );

        // Assert
        self::assertTrue($result);
    }
}
