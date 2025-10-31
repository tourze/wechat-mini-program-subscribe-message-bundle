<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

/**
 * @internal
 */
#[CoversClass(SubscribeTemplateData::class)]
final class SubscribeTemplateDataTest extends AbstractEnumTestCase
{
    public function testEnumHasExpectedCases(): void
    {
        $cases = SubscribeTemplateData::cases();

        self::assertCount(13, $cases);
        self::assertSame('thing', SubscribeTemplateData::THING->value);
        self::assertSame('number', SubscribeTemplateData::NUMBER->value);
        self::assertSame('letter', SubscribeTemplateData::LETTER->value);
        self::assertSame('symbol', SubscribeTemplateData::SYMBOL->value);
        self::assertSame('character_string', SubscribeTemplateData::CHARACTER_STRING->value);
        self::assertSame('time', SubscribeTemplateData::TIME->value);
        self::assertSame('date', SubscribeTemplateData::DATE->value);
        self::assertSame('amount', SubscribeTemplateData::AMOUNT->value);
        self::assertSame('phone_number', SubscribeTemplateData::PHONE_NUMBER->value);
        self::assertSame('car_number', SubscribeTemplateData::CAR_NUMBER->value);
        self::assertSame('name', SubscribeTemplateData::NAME->value);
        self::assertSame('phrase', SubscribeTemplateData::PHRASE->value);
        self::assertSame('enum', SubscribeTemplateData::ENUM->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        self::assertSame('事物', SubscribeTemplateData::THING->getLabel());
        self::assertSame('数字', SubscribeTemplateData::NUMBER->getLabel());
        self::assertSame('字母', SubscribeTemplateData::LETTER->getLabel());
        self::assertSame('符号', SubscribeTemplateData::SYMBOL->getLabel());
        self::assertSame('字符串', SubscribeTemplateData::CHARACTER_STRING->getLabel());
        self::assertSame('时间', SubscribeTemplateData::TIME->getLabel());
        self::assertSame('日期', SubscribeTemplateData::DATE->getLabel());
        self::assertSame('金额', SubscribeTemplateData::AMOUNT->getLabel());
        self::assertSame('电话', SubscribeTemplateData::PHONE_NUMBER->getLabel());
        self::assertSame('车牌', SubscribeTemplateData::CAR_NUMBER->getLabel());
        self::assertSame('姓名', SubscribeTemplateData::NAME->getLabel());
        self::assertSame('汉字', SubscribeTemplateData::PHRASE->getLabel());
        self::assertSame('枚举值', SubscribeTemplateData::ENUM->getLabel());
    }

    public function testGetMaxLengthReturnsCorrectLimits(): void
    {
        self::assertSame(20, SubscribeTemplateData::THING->getMaxLength());
        self::assertSame(32, SubscribeTemplateData::NUMBER->getMaxLength());
        self::assertSame(32, SubscribeTemplateData::LETTER->getMaxLength());
        self::assertSame(5, SubscribeTemplateData::SYMBOL->getMaxLength());
        self::assertSame(32, SubscribeTemplateData::CHARACTER_STRING->getMaxLength());
        self::assertSame(0, SubscribeTemplateData::TIME->getMaxLength());
        self::assertSame(0, SubscribeTemplateData::DATE->getMaxLength());
        self::assertSame(0, SubscribeTemplateData::AMOUNT->getMaxLength());
        self::assertSame(17, SubscribeTemplateData::PHONE_NUMBER->getMaxLength());
        self::assertSame(8, SubscribeTemplateData::CAR_NUMBER->getMaxLength());
        self::assertSame(10, SubscribeTemplateData::NAME->getMaxLength());
        self::assertSame(5, SubscribeTemplateData::PHRASE->getMaxLength());
        self::assertSame(0, SubscribeTemplateData::ENUM->getMaxLength());
    }

    public function testToArrayReturnsCorrectArray(): void
    {
        $thingArray = SubscribeTemplateData::THING->toArray();

        self::assertIsArray($thingArray);
        self::assertCount(2, $thingArray);
        self::assertArrayHasKey('value', $thingArray);
        self::assertArrayHasKey('label', $thingArray);
        self::assertSame('thing', $thingArray['value']);
        self::assertSame('事物', $thingArray['label']);

        $numberArray = SubscribeTemplateData::NUMBER->toArray();
        self::assertSame('number', $numberArray['value']);
        self::assertSame('数字', $numberArray['label']);
    }

    public function testToSelectItemReturnsCorrectFormat(): void
    {
        $thingItem = SubscribeTemplateData::THING->toSelectItem();

        self::assertIsArray($thingItem);
        self::assertArrayHasKey('value', $thingItem);
        self::assertArrayHasKey('label', $thingItem);
        self::assertArrayHasKey('text', $thingItem);
        self::assertArrayHasKey('name', $thingItem);

        self::assertSame('thing', $thingItem['value']);
        self::assertSame('事物', $thingItem['label']);
        self::assertSame('事物', $thingItem['text']);
        self::assertSame('事物', $thingItem['name']);

        $numberItem = SubscribeTemplateData::NUMBER->toSelectItem();
        self::assertSame('number', $numberItem['value']);
        self::assertSame('数字', $numberItem['label']);
    }

    public function testGenOptionsReturnsAllItems(): void
    {
        $options = SubscribeTemplateData::genOptions();

        self::assertIsArray($options);
        self::assertCount(13, $options);

        foreach ($options as $option) {
            self::assertIsArray($option);
            self::assertArrayHasKey('value', $option);
            self::assertArrayHasKey('label', $option);
            self::assertArrayHasKey('text', $option);
            self::assertArrayHasKey('name', $option);
        }

        $thingOption = null;
        foreach ($options as $option) {
            if ('thing' === $option['value']) {
                $thingOption = $option;
                break;
            }
        }

        self::assertNotNull($thingOption);
        self::assertSame('thing', $thingOption['value']);
        self::assertSame('事物', $thingOption['label']);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (SubscribeTemplateData::cases() as $case) {
            $value = $case->value;
            self::assertNotContains($value, $values, "Duplicate value found: {$value}");
            $values[] = $value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (SubscribeTemplateData::cases() as $case) {
            $label = $case->getLabel();
            self::assertNotContains($label, $labels, "Duplicate label found: {$label}");
            $labels[] = $label;
        }
    }
}
