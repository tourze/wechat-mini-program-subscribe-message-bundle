<?php

namespace WechatMiniProgramSubscribeMessageBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('推送管理')) {
            $item->addChild('推送管理');
        }
        $pushManagement = $item->getChild('推送管理');
        if (null !== $pushManagement) {
            $pushManagement->addChild('订阅分类')->setUri($this->linkGenerator->getCurdListPage(SubscribeCategory::class));
            $pushManagement->addChild('小程序模板')->setUri($this->linkGenerator->getCurdListPage(SubscribeTemplate::class));
            $pushManagement->addChild('模板参数')->setUri($this->linkGenerator->getCurdListPage(SubscribeParam::class));
            $pushManagement->addChild('发送模板消息')->setUri('/admin/wechat-mini-program-subscribe-message/send');
            $pushManagement->addChild('发送日志')->setUri($this->linkGenerator->getCurdListPage(SendSubscribeLog::class));
            $pushManagement->addChild('订阅结果日志')->setUri($this->linkGenerator->getCurdListPage(SubscribeMessageLog::class));
        }
    }
}
