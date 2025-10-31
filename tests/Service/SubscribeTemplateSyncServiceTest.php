<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeTemplateSyncService;

/**
 * @internal
 */
#[CoversClass(SubscribeTemplateSyncService::class)]
#[RunTestsInSeparateProcesses]
class SubscribeTemplateSyncServiceTest extends AbstractIntegrationTestCase
{
    private SubscribeTemplateSyncService $service;

    protected function onSetUp(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('request')->willReturn([
            'data' => [
                [
                    'priTmplId' => 'test-template-1',
                    'type' => 2,
                    'title' => '测试模板1',
                    'content' => '{{phrase1.DATA}} 测试内容',
                    'example' => '示例内容',
                    'keywordEnumValueList' => [],
                ],
                [
                    'priTmplId' => 'test-template-2',
                    'type' => 3,
                    'title' => '测试模板2',
                    'content' => '{{name1.DATA}} 测试内容2',
                    'example' => '示例内容2',
                    'keywordEnumValueList' => [],
                ],
            ],
        ]);

        self::getContainer()->set(Client::class, $mockClient);

        $this->service = self::getService(SubscribeTemplateSyncService::class);
    }

    public function testServiceCanBeInstantiated(): void
    {
        $this->assertInstanceOf(SubscribeTemplateSyncService::class, $this->service);
    }

    public function testSyncAllAccountsReturnsValidResult(): void
    {
        $result = $this->service->syncAllAccounts();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('syncCount', $result);
        $this->assertArrayHasKey('errorCount', $result);
        $this->assertArrayHasKey('errors', $result);
    }

    public function testSyncTemplatesForAccount(): void
    {
        $account = new Account();
        $account->setName('测试账户');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-secret');
        $account->setValid(true);

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $result = $this->service->syncTemplatesForAccount($account);

        $this->assertIsInt($result);
        $this->assertSame(2, $result);
    }
}
