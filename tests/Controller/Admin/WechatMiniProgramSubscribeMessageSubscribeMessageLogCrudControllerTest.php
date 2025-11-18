<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

/**
 * @internal
 */
#[RunTestsInSeparateProcesses]
#[CoversClass(WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController::class)]
final class WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    #[Test]
    protected function getControllerService(): WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController
    {
        return new WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID列' => ['ID'];
        yield '模板ID列' => ['模板ID'];
        yield '订阅状态列' => ['订阅状态'];
        yield '来源IP列' => ['来源IP'];
        yield '创建时间列' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield '微信小程序账号字段' => ['account'];
        yield '用户字段' => ['user'];
        yield '模板ID字段' => ['templateId'];
        yield '订阅状态字段' => ['subscribeStatus'];
        yield '发送结果MsgId字段' => ['resultMsgId'];
        yield '发送结果错误码字段' => ['resultCode'];
        yield '发送结果状态字段' => ['resultStatus'];
        yield '原始数据字段' => ['rawData'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '微信小程序账号字段' => ['account'];
        yield '用户字段' => ['user'];
        yield '模板ID字段' => ['templateId'];
        yield '订阅状态字段' => ['subscribeStatus'];
        yield '发送结果MsgId字段' => ['resultMsgId'];
        yield '发送结果错误码字段' => ['resultCode'];
        yield '发送结果状态字段' => ['resultStatus'];
        yield '原始数据字段' => ['rawData'];
    }
}
