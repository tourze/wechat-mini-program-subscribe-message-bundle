<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;

/**
 * Mock 的 LinkGenerator 实现，用于测试
 */
class MockLinkGenerator implements LinkGeneratorInterface
{
    public function getCurdListPage(string $entityClass): string
    {
        return match ($entityClass) {
            'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory' => '/category-url',
            'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate' => '/template-url',
            'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam' => '/param-url',
            'WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog' => '/send-log-url',
            'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog' => '/log-url',
            default => '/admin/default',
        };
    }

    public function extractEntityFqcn(string $url): ?string
    {
        return match ($url) {
            '/category-url' => 'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory',
            '/template-url' => 'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate',
            '/param-url' => 'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam',
            '/send-log-url' => 'WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog',
            '/log-url' => 'WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog',
            default => null,
        };
    }

    public function setDashboard(string $dashboardControllerFqcn): void
    {
        // Mock implementation - no action needed
    }
}
