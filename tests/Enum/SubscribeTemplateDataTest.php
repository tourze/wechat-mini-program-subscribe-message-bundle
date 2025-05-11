<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

class SubscribeTemplateDataTest extends TestCase
{
    public function testEnum_hasExpectedCases()
    {
        $cases = SubscribeTemplateData::cases();

        $this->assertCount(13, $cases);
        $this->assertSame('thing', SubscribeTemplateData::THING->value);
        $this->assertSame('number', SubscribeTemplateData::NUMBER->value);
        $this->assertSame('letter', SubscribeTemplateData::LETTER->value);
        $this->assertSame('symbol', SubscribeTemplateData::SYMBOL->value);
        $this->assertSame('character_string', SubscribeTemplateData::CHARACTER_STRING->value);
        $this->assertSame('time', SubscribeTemplateData::TIME->value);
        $this->assertSame('date', SubscribeTemplateData::DATE->value);
        $this->assertSame('amount', SubscribeTemplateData::AMOUNT->value);
        $this->assertSame('phone_number', SubscribeTemplateData::PHONE_NUMBER->value);
        $this->assertSame('car_number', SubscribeTemplateData::CAR_NUMBER->value);
        $this->assertSame('name', SubscribeTemplateData::NAME->value);
        $this->assertSame('phrase', SubscribeTemplateData::PHRASE->value);
        $this->assertSame('enum', SubscribeTemplateData::ENUM->value);
    }

    public function testGetLabel_returnsCorrectLabels()
    {
        $this->assertSame('事物', SubscribeTemplateData::THING->getLabel());
        $this->assertSame('数字', SubscribeTemplateData::NUMBER->getLabel());
        $this->assertSame('字母', SubscribeTemplateData::LETTER->getLabel());
        $this->assertSame('符号', SubscribeTemplateData::SYMBOL->getLabel());
        $this->assertSame('字符串', SubscribeTemplateData::CHARACTER_STRING->getLabel());
        $this->assertSame('时间', SubscribeTemplateData::TIME->getLabel());
        $this->assertSame('日期', SubscribeTemplateData::DATE->getLabel());
        $this->assertSame('金额', SubscribeTemplateData::AMOUNT->getLabel());
        $this->assertSame('电话', SubscribeTemplateData::PHONE_NUMBER->getLabel());
        $this->assertSame('车牌', SubscribeTemplateData::CAR_NUMBER->getLabel());
        $this->assertSame('姓名', SubscribeTemplateData::NAME->getLabel());
        $this->assertSame('汉字', SubscribeTemplateData::PHRASE->getLabel());
        $this->assertSame('枚举值', SubscribeTemplateData::ENUM->getLabel());
    }

    public function testGetMaxLength_returnsCorrectLimits()
    {
        $this->assertSame(20, SubscribeTemplateData::THING->getMaxLength());
        $this->assertSame(32, SubscribeTemplateData::NUMBER->getMaxLength());
        $this->assertSame(32, SubscribeTemplateData::LETTER->getMaxLength());
        $this->assertSame(5, SubscribeTemplateData::SYMBOL->getMaxLength());
        $this->assertSame(32, SubscribeTemplateData::CHARACTER_STRING->getMaxLength());
        $this->assertSame(0, SubscribeTemplateData::TIME->getMaxLength());
        $this->assertSame(0, SubscribeTemplateData::DATE->getMaxLength());
        $this->assertSame(0, SubscribeTemplateData::AMOUNT->getMaxLength());
        $this->assertSame(17, SubscribeTemplateData::PHONE_NUMBER->getMaxLength());
        $this->assertSame(8, SubscribeTemplateData::CAR_NUMBER->getMaxLength());
        $this->assertSame(10, SubscribeTemplateData::NAME->getMaxLength());
        $this->assertSame(5, SubscribeTemplateData::PHRASE->getMaxLength());
        $this->assertSame(0, SubscribeTemplateData::ENUM->getMaxLength());
    }
}
