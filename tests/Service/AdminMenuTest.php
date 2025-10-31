<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatMiniProgramSubscribeMessageBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses] final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    private LinkGeneratorInterface $linkGenerator;

    protected function onSetUp(): void
    {
        $this->linkGenerator = new MockLinkGenerator();
        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testInvokeCreatesMenuItems(): void
    {
        // Arrange
        $parentItem = $this->createMock(ItemInterface::class);
        $pushManagementItem = $this->createMock(ItemInterface::class);
        $categoryItem = $this->createMock(ItemInterface::class);
        $templateItem = $this->createMock(ItemInterface::class);
        $paramItem = $this->createMock(ItemInterface::class);
        $sendMessageItem = $this->createMock(ItemInterface::class);
        $sendLogItem = $this->createMock(ItemInterface::class);
        $subscribeLogItem = $this->createMock(ItemInterface::class);

        $parentItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('推送管理')
            ->willReturnOnConsecutiveCalls(null, $pushManagementItem)
        ;

        $parentItem->expects($this->once())
            ->method('addChild')
            ->with('推送管理')
            ->willReturn($pushManagementItem)
        ;

        $pushManagementItem->expects($this->exactly(6))
            ->method('addChild')
            ->willReturnMap([
                ['订阅分类', [], $categoryItem],
                ['小程序模板', [], $templateItem],
                ['模板参数', [], $paramItem],
                ['发送模板消息', [], $sendMessageItem],
                ['发送日志', [], $sendLogItem],
                ['订阅结果日志', [], $subscribeLogItem],
            ])
        ;

        $categoryItem->expects($this->once())
            ->method('setUri')
            ->with('/category-url')
        ;

        $templateItem->expects($this->once())
            ->method('setUri')
            ->with('/template-url')
        ;

        $paramItem->expects($this->once())
            ->method('setUri')
            ->with('/param-url')
        ;

        $sendMessageItem->expects($this->once())
            ->method('setUri')
            ->with('/admin/wechat-mini-program-subscribe-message/send')
        ;

        $sendLogItem->expects($this->once())
            ->method('setUri')
            ->with('/send-log-url')
        ;

        $subscribeLogItem->expects($this->once())
            ->method('setUri')
            ->with('/log-url')
        ;

        // Act
        ($this->adminMenu)($parentItem);

        // Assert - Mock expectations verified implicitly via PHPUnit
        // Mock expectations are verified automatically by PHPUnit
    }

    public function testInvokeWithExistingPushManagementMenu(): void
    {
        // Arrange
        $parentItem = $this->createMock(ItemInterface::class);
        $pushManagementItem = $this->createMock(ItemInterface::class);
        $categoryItem = $this->createMock(ItemInterface::class);
        $templateItem = $this->createMock(ItemInterface::class);
        $paramItem = $this->createMock(ItemInterface::class);
        $sendMessageItem = $this->createMock(ItemInterface::class);
        $sendLogItem = $this->createMock(ItemInterface::class);
        $subscribeLogItem = $this->createMock(ItemInterface::class);

        $parentItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('推送管理')
            ->willReturn($pushManagementItem)
        ;

        $parentItem->expects($this->never())
            ->method('addChild')
        ;

        $pushManagementItem->expects($this->exactly(6))
            ->method('addChild')
            ->willReturnMap([
                ['订阅分类', [], $categoryItem],
                ['小程序模板', [], $templateItem],
                ['模板参数', [], $paramItem],
                ['发送模板消息', [], $sendMessageItem],
                ['发送日志', [], $sendLogItem],
                ['订阅结果日志', [], $subscribeLogItem],
            ])
        ;

        $categoryItem->expects($this->once())
            ->method('setUri')
            ->with('/category-url')
        ;

        $templateItem->expects($this->once())
            ->method('setUri')
            ->with('/template-url')
        ;

        $paramItem->expects($this->once())
            ->method('setUri')
            ->with('/param-url')
        ;

        $sendMessageItem->expects($this->once())
            ->method('setUri')
            ->with('/admin/wechat-mini-program-subscribe-message/send')
        ;

        $sendLogItem->expects($this->once())
            ->method('setUri')
            ->with('/send-log-url')
        ;

        $subscribeLogItem->expects($this->once())
            ->method('setUri')
            ->with('/log-url')
        ;

        // Act
        ($this->adminMenu)($parentItem);

        // Assert - Mock expectations verified implicitly via PHPUnit
        // Mock expectations are verified automatically by PHPUnit
    }

    public function testConstructor(): void
    {
        // 验证 AdminMenu 实例创建成功，依赖注入正常
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }
}
