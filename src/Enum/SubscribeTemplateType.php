<?php

namespace WechatMiniProgramSubscribeMessageBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 订阅模版类型
 */
enum SubscribeTemplateType: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case ONCE = '2';
    case LONG = '3';

    public function getLabel(): string
    {
        return match ($this) {
            self::ONCE => '一次性订阅',
            self::LONG => '长期订阅',
        };
    }
}
