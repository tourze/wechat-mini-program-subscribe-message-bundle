<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeParamCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(WechatMiniProgramSubscribeMessageSubscribeParamCrudController::class)]
final class WechatMiniProgramSubscribeMessageSubscribeParamCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testGetEntityFqcn(): void
    {
        $client = self::createAuthenticatedClient();

        // 访问任意admin页面以确保Controller已加载
        $client->request('GET', '/admin');

        // 然后测试静态方法
        $result = WechatMiniProgramSubscribeMessageSubscribeParamCrudController::getEntityFqcn();
        self::assertSame(SubscribeParam::class, $result);
    }

    protected function getControllerService(): WechatMiniProgramSubscribeMessageSubscribeParamCrudController
    {
        return self::getService(WechatMiniProgramSubscribeMessageSubscribeParamCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '参数类型' => ['参数类型'];
        yield '参数代码' => ['参数代码'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'template' => ['template'];
        yield 'type' => ['type'];
        yield 'code' => ['code'];
        yield 'mapExpression' => ['mapExpression'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '订阅模板' => ['template'];
        yield '参数类型' => ['type'];
        yield '参数代码' => ['code'];
        yield '数据映射表达式' => ['mapExpression'];
    }
}
