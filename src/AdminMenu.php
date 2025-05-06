<?php

namespace WechatMiniProgramSubscribeMessageBundle;

use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Attribute\MenuProvider;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

#[MenuProvider]
class AdminMenu
{
    public function __construct(private readonly LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (!$item->getChild('推送管理')) {
            $item->addChild('推送管理');
        }
        $item->getChild('推送管理')->addChild('小程序模板')->setUri($this->linkGenerator->getCurdListPage(SubscribeTemplate::class));
        $item->getChild('推送管理')->addChild('订阅结果日志')->setUri($this->linkGenerator->getCurdListPage(SubscribeMessageLog::class));
    }
}
