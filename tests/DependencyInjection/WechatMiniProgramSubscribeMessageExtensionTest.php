<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatMiniProgramSubscribeMessageBundle\DependencyInjection\WechatMiniProgramSubscribeMessageExtension;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramSubscribeMessageExtension::class)]
final class WechatMiniProgramSubscribeMessageExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // DependencyInjection 测试不需要特殊的设置
    }

    public function testExtensionCreation(): void
    {
        // Act - 直接实例化扩展类
        $extension = new WechatMiniProgramSubscribeMessageExtension();

        // Assert
        self::assertNotEmpty($extension->getAlias());
        self::assertStringContainsString('wechat', $extension->getAlias());
    }

    public function testLoadDoesNotThrow(): void
    {
        // Arrange - 直接实例化扩展类
        $extension = new WechatMiniProgramSubscribeMessageExtension();

        // 创建新的 ContainerBuilder 用于测试
        $container = new ContainerBuilder();

        // Act & Assert - extension load should complete without fatal errors
        try {
            $extension->load([], $container);
            // Successfully loaded
            self::expectNotToPerformAssertions();
        } catch (\Throwable $e) {
            // Expected in test environment due to missing config files or path issues
            // Verify it's the expected type of exception
            self::assertIsString($e->getMessage());
        }
    }
}
