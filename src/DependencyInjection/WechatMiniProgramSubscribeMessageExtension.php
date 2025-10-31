<?php

namespace WechatMiniProgramSubscribeMessageBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class WechatMiniProgramSubscribeMessageExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return \dirname(__DIR__) . '/Resources/config';
    }
}
