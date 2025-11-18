<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController::class)]
final class WechatMiniProgramSubscribeMessageSubscribeCategoryCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testSync(): void
    {
        $client = self::createAuthenticatedClient();

        $client->request('GET', '/admin/wechat-mini-program-subscribe-message/subscribe-category/sync');

        // 验证返回正确的状态码（重定向或访问被拒绝都是可接受的）
        self::assertContains($client->getResponse()->getStatusCode(), [302, 403]);
    }

    protected function getControllerService(): WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController
    {
        return self::getService(WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '类目ID' => ['类目ID'];
        yield '类目名称' => ['类目名称'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'categoryId' => ['categoryId'];
        yield 'name' => ['name'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'categoryId' => ['categoryId'];
        yield 'name' => ['name'];
    }
}
