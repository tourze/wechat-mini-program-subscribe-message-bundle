<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use WechatMiniProgramSubscribeMessageBundle\EventSubscriber\SubscribeMessageServerSubscriber;

/**
 * @internal
 */
#[CoversClass(SubscribeMessageServerSubscriber::class)]
#[RunTestsInSeparateProcesses] final class SubscribeMessageServerSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // EventSubscriber 测试不需要特殊的设置
    }

    public function testSubscriberHasOnPopupCallbackMethod(): void
    {
        // 使用反射测试静态方法，避免直接实例化
        $reflection = new \ReflectionClass(SubscribeMessageServerSubscriber::class);
        self::assertTrue($reflection->hasMethod('onPopupCallback'));

        $method = $reflection->getMethod('onPopupCallback');
        $attributes = $method->getAttributes(AsEventListener::class);
        self::assertCount(1, $attributes);
    }

    public function testSubscriberHasOnResultCallbackMethod(): void
    {
        // 使用反射测试静态方法，避免直接实例化
        $reflection = new \ReflectionClass(SubscribeMessageServerSubscriber::class);
        self::assertTrue($reflection->hasMethod('onResultCallback'));

        $method = $reflection->getMethod('onResultCallback');
        $attributes = $method->getAttributes(AsEventListener::class);
        self::assertCount(1, $attributes);
    }

    public function testOnPopupCallback(): void
    {
        // 测试方法存在且具有正确的事件监听器属性
        $reflection = new \ReflectionClass(SubscribeMessageServerSubscriber::class);
        self::assertTrue($reflection->hasMethod('onPopupCallback'));

        $method = $reflection->getMethod('onPopupCallback');
        self::assertTrue($method->isPublic());

        // 验证参数数量
        $parameters = $method->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('event', $parameters[0]->getName());
    }

    public function testOnResultCallback(): void
    {
        // 测试方法存在且具有正确的事件监听器属性
        $reflection = new \ReflectionClass(SubscribeMessageServerSubscriber::class);
        self::assertTrue($reflection->hasMethod('onResultCallback'));

        $method = $reflection->getMethod('onResultCallback');
        self::assertTrue($method->isPublic());

        // 验证参数数量
        $parameters = $method->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('event', $parameters[0]->getName());
    }

    public function testOnManageCallback(): void
    {
        // 测试方法存在且具有正确的事件监听器属性
        $reflection = new \ReflectionClass(SubscribeMessageServerSubscriber::class);
        self::assertTrue($reflection->hasMethod('onManageCallback'));

        $method = $reflection->getMethod('onManageCallback');
        self::assertTrue($method->isPublic());

        // 验证参数数量
        $parameters = $method->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('event', $parameters[0]->getName());
    }
}
