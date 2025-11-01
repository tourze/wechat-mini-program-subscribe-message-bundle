<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController::class)]
final class WechatMiniProgramSubscribeMessageSendSubscribeLogCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    public function testGetEntityFqcn(): void
    {
        $client = self::createAuthenticatedClient();

        // 访问任意admin页面以确保Controller已加载
        $client->request('GET', '/admin');

        // 然后测试静态方法
        $result = WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController::getEntityFqcn();
        self::assertSame(SendSubscribeLog::class, $result);
    }

    protected function getControllerService(): WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController
    {
        return self::getService(WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '模板ID' => ['模板ID'];
        yield '来源IP' => ['来源IP'];
        yield '创建时间' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'user' => ['user'];
        yield 'templateId' => ['templateId'];
        yield 'subscribeTemplate' => ['subscribeTemplate'];
        yield 'result' => ['result'];
        yield 'remark' => ['remark'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'account' => ['account'];
        yield 'user' => ['user'];
        yield 'templateId' => ['templateId'];
        yield 'subscribeTemplate' => ['subscribeTemplate'];
        yield 'result' => ['result'];
        yield 'remark' => ['remark'];
    }
}
