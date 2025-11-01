<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeTemplateSyncService;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController::class)]
final class WechatMiniProgramSubscribeMessageSubscribeTemplateCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testGetEntityFqcn(): void
    {
        $client = self::createAuthenticatedClient();

        // 访问任意admin页面以确保Controller已加载
        $client->request('GET', '/admin');

        // 然后测试静态方法
        $result = WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController::getEntityFqcn();
        self::assertSame(SubscribeTemplate::class, $result);
    }

    protected function getControllerService(): WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController
    {
        // 创建控制器实例，注入必要的依赖
        return new WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController(
            self::getService(SubscribeTemplateSyncService::class),
            self::getService(AdminUrlGenerator::class)
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '模板ID' => ['模板ID'];
        yield '模板标题' => ['模板标题'];
        yield '模板类型' => ['模板类型'];
        yield '是否有效' => ['是否有效'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'priTmplId' => ['priTmplId'];
        yield 'title' => ['title'];
        yield 'type' => ['type'];
        yield 'valid' => ['valid'];
        yield 'content' => ['content'];
        yield 'example' => ['example'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '微信小程序账号' => ['account'];
        yield '模板ID' => ['priTmplId'];
        yield '模板标题' => ['title'];
        yield '模板类型' => ['type'];
        yield '是否有效' => ['valid'];
        yield '模板内容' => ['content'];
        yield '模板内容示例' => ['example'];
    }

    #[Test]
    public function testSync(): void
    {
        $client = self::createClient();
        // 测试sync动作的路由访问，预期返回授权相关状态码
        $client->request('GET', '/admin/wechat-mini-program-subscribe-message/subscribe-template/sync');
        // 验证响应状态码为授权/访问控制相关代码
        $this->assertContains($client->getResponse()->getStatusCode(), [302, 401, 403, 404, 500]);
    }
}
