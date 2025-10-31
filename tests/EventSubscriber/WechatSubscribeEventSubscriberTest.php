<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\EventSubscriber;

use HttpClientBundle\Event\AfterAsyncHttpClientEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use WechatMiniProgramSubscribeMessageBundle\EventSubscriber\WechatSubscribeEventSubscriber;

/**
 * @internal
 */
#[CoversClass(WechatSubscribeEventSubscriber::class)]
#[RunTestsInSeparateProcesses] final class WechatSubscribeEventSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // EventSubscriber 测试不需要特殊的设置
    }

    public function testSubscriberHasAfterAsyncHttpRequestMethod(): void
    {
        // 使用反射测试静态方法，避免直接实例化
        $reflection = new \ReflectionClass(WechatSubscribeEventSubscriber::class);
        self::assertTrue($reflection->hasMethod('afterAsyncHttpRequest'));

        $method = $reflection->getMethod('afterAsyncHttpRequest');
        $attributes = $method->getAttributes(AsEventListener::class);
        self::assertCount(1, $attributes);
    }

    public function testAfterAsyncHttpRequest(): void
    {
        // 测试方法存在且具有正确的事件监听器属性
        $reflection = new \ReflectionClass(WechatSubscribeEventSubscriber::class);
        self::assertTrue($reflection->hasMethod('afterAsyncHttpRequest'));

        $method = $reflection->getMethod('afterAsyncHttpRequest');
        self::assertTrue($method->isPublic());

        // 验证参数类型
        $parameters = $method->getParameters();
        self::assertCount(1, $parameters);
        self::assertEquals('event', $parameters[0]->getName());

        $type = $parameters[0]->getType();
        if ($type instanceof \ReflectionNamedType) {
            self::assertEquals(AfterAsyncHttpClientEvent::class, $type->getName());
        }
    }
}
