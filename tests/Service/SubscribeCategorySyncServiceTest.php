<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeCategorySyncService;

/**
 * @internal
 */
#[CoversClass(SubscribeCategorySyncService::class)]
#[RunTestsInSeparateProcesses]
class SubscribeCategorySyncServiceTest extends AbstractIntegrationTestCase
{
    private SubscribeCategorySyncService $service;

    protected function onSetUp(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->method('request')->willReturn([
            ['id' => 1, 'name' => '测试分类1'],
            ['id' => 2, 'name' => '测试分类2'],
        ]);

        self::getContainer()->set(Client::class, $mockClient);

        $this->service = self::getService(SubscribeCategorySyncService::class);
    }

    public function testServiceCanBeInstantiated(): void
    {
        $this->assertInstanceOf(SubscribeCategorySyncService::class, $this->service);
    }

    public function testSyncAllAccountsReturnsValidResult(): void
    {
        $result = $this->service->syncAllAccounts();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('syncCount', $result);
        $this->assertArrayHasKey('errorCount', $result);
        $this->assertArrayHasKey('errors', $result);
    }

    public function testSyncCategoriesForAccount(): void
    {
        $account = new Account();
        $account->setName('测试账户');
        $account->setAppId('test-app-id');
        $account->setAppSecret('test-secret');
        $account->setValid(true);

        self::getEntityManager()->persist($account);
        self::getEntityManager()->flush();

        $result = $this->service->syncCategoriesForAccount($account);

        $this->assertIsInt($result);
        $this->assertSame(2, $result);
    }
}
