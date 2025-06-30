<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Unit;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use WechatMiniProgramSubscribeMessageBundle\AdminMenu;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

class AdminMenuTest extends TestCase
{
    public function testInvokeCreatesMenuItems()
    {
        // Arrange
        $linkGenerator = $this->createMock(LinkGeneratorInterface::class);
        $linkGenerator->expects($this->exactly(2))
            ->method('getCurdListPage')
            ->willReturnMap([
                [SubscribeTemplate::class, '/template-url'],
                [SubscribeMessageLog::class, '/log-url']
            ]);

        $parentItem = $this->createMock(ItemInterface::class);
        $pushManagementItem = $this->createMock(ItemInterface::class);
        $templateItem = $this->createMock(ItemInterface::class);
        $logItem = $this->createMock(ItemInterface::class);

        $parentItem->expects($this->exactly(3))
            ->method('getChild')
            ->with('推送管理')
            ->willReturnOnConsecutiveCalls(null, $pushManagementItem, $pushManagementItem);

        $parentItem->expects($this->once())
            ->method('addChild')
            ->with('推送管理')
            ->willReturn($pushManagementItem);

        $pushManagementItem->expects($this->exactly(2))
            ->method('addChild')
            ->willReturnMap([
                ['小程序模板', $templateItem],
                ['订阅结果日志', $logItem]
            ]);

        $templateItem->expects($this->once())
            ->method('setUri')
            ->with('/template-url')
            ->willReturnSelf();

        $logItem->expects($this->once())
            ->method('setUri')
            ->with('/log-url')
            ->willReturnSelf();

        $adminMenu = new AdminMenu($linkGenerator);

        // Act
        $adminMenu($parentItem);

        // Assert - expectations are verified by the mock framework
    }

    public function testInvokeWithExistingPushManagementMenu()
    {
        // Arrange
        $linkGenerator = $this->createMock(LinkGeneratorInterface::class);
        $linkGenerator->expects($this->exactly(2))
            ->method('getCurdListPage')
            ->willReturnMap([
                [SubscribeTemplate::class, '/template-url'],
                [SubscribeMessageLog::class, '/log-url']
            ]);

        $parentItem = $this->createMock(ItemInterface::class);
        $pushManagementItem = $this->createMock(ItemInterface::class);
        $templateItem = $this->createMock(ItemInterface::class);
        $logItem = $this->createMock(ItemInterface::class);

        $parentItem->expects($this->exactly(3))
            ->method('getChild')
            ->with('推送管理')
            ->willReturn($pushManagementItem);

        $parentItem->expects($this->never())
            ->method('addChild');

        $pushManagementItem->expects($this->exactly(2))
            ->method('addChild')
            ->willReturnMap([
                ['小程序模板', $templateItem],
                ['订阅结果日志', $logItem]
            ]);

        $templateItem->expects($this->once())
            ->method('setUri')
            ->with('/template-url')
            ->willReturnSelf();

        $logItem->expects($this->once())
            ->method('setUri')
            ->with('/log-url')
            ->willReturnSelf();

        $adminMenu = new AdminMenu($linkGenerator);

        // Act
        $adminMenu($parentItem);

        // Assert - expectations are verified by the mock framework
    }
}