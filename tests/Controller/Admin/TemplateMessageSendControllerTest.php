<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tourze\PHPUnitSymfonyWebTest\AbstractWebTestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\TemplateMessageSendController;

/**
 * @internal
 */
#[CoversClass(TemplateMessageSendController::class)]
#[RunTestsInSeparateProcesses]
final class TemplateMessageSendControllerTest extends AbstractWebTestCase
{
    protected function onSetUp(): void
    {
    }

    #[DataProvider('provideNotAllowedMethods')]
    public function testMethodNotAllowed(string $method): void
    {
        $client = self::createClientWithDatabase();
        $admin = $this->createAdminUser('admin@test.com', 'password');
        $this->loginAsAdmin($client, 'admin@test.com', 'password');

        // 对于INVALID方法，预期会抛出异常
        // 因为这是一个无效的HTTP方法，路由器无法识别
        if ('INVALID' === $method) {
            $this->expectException(NotFoundHttpException::class);
            $client->request($method, '/admin/wechat-mini-program-subscribe-message/send');

            return;
        }

        // 测试不允许的 HTTP 方法
        $client->request($method, '/admin/wechat-mini-program-subscribe-message/send');
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function testControllerIsFinal(): void
    {
        $reflection = new \ReflectionClass(TemplateMessageSendController::class);
        $this->assertTrue($reflection->isFinal());
    }
}
