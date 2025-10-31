<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramSubscribeMessageBundle\Service\TemplateMessageSendService;

/**
 * @internal
 */
#[CoversClass(TemplateMessageSendService::class)]
#[RunTestsInSeparateProcesses]
class TemplateMessageSendServiceTest extends AbstractIntegrationTestCase
{
    private TemplateMessageSendService $service;

    protected function onSetUp(): void
    {
        $this->service = self::getService(TemplateMessageSendService::class);
    }

    public function testServiceCanBeInstantiated(): void
    {
        $this->assertInstanceOf(TemplateMessageSendService::class, $this->service);
    }

    public function testGetAvailableTemplatesReturnsArray(): void
    {
        $templates = $this->service->getAvailableTemplates();

        $this->assertIsArray($templates);
    }

    public function testGetTemplateParamsReturnsArray(): void
    {
        $params = $this->service->getTemplateParams(999); // Non-existent ID

        $this->assertIsArray($params);
        $this->assertEmpty($params); // Should return empty array for non-existent template
    }

    public function testSendTemplateMessage(): void
    {
        $result = $this->service->sendTemplateMessage(
            999, // Non-existent template ID
            'test-union-id',
            ['test' => 'data']
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertFalse($result['success']); // Should fail for non-existent template
    }
}
