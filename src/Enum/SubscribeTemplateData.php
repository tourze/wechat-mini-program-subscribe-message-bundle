<?php

namespace WechatMiniProgramSubscribeMessageBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 订阅消息参数值内容限制
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/sendMessage.html
 */
enum SubscribeTemplateData: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case THING = 'thing';
    case NUMBER = 'number';
    case LETTER = 'letter';
    case SYMBOL = 'symbol';
    case CHARACTER_STRING = 'character_string';
    case TIME = 'time';
    case DATE = 'date';
    case AMOUNT = 'amount';
    case PHONE_NUMBER = 'phone_number';
    case CAR_NUMBER = 'car_number';
    case NAME = 'name';
    case PHRASE = 'phrase';
    case ENUM = 'enum';

    public function getLabel(): string
    {
        return match ($this) {
            self::THING => '事物',
            self::NUMBER => '数字',
            self::LETTER => '字母',
            self::SYMBOL => '符号',
            self::CHARACTER_STRING => '字符串',
            self::TIME => '时间',
            self::DATE => '日期',
            self::AMOUNT => '金额',
            self::PHONE_NUMBER => '电话',
            self::CAR_NUMBER => '车牌',
            self::NAME => '姓名',
            self::PHRASE => '汉字',
            self::ENUM => '枚举值',
        };
    }

    /**
     * 获取数据长度限制
     */
    public function getMaxLength(): int
    {
        return match ($this) {
            self::THING => 20,
            self::NUMBER, self::LETTER, self::CHARACTER_STRING => 32,
            self::SYMBOL, self::PHRASE => 5,
            self::TIME, self::AMOUNT, self::DATE, self::ENUM => 0,
            self::PHONE_NUMBER => 17,
            self::CAR_NUMBER => 8,
            self::NAME => 10,
        };
    }
}
