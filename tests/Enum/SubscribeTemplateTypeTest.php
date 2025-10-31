<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;

/**
 * @internal
 */
#[CoversClass(SubscribeTemplateType::class)]
final class SubscribeTemplateTypeTest extends AbstractEnumTestCase
{
    public function testEnumHasExpectedCases(): void
    {
        $cases = SubscribeTemplateType::cases();

        self::assertCount(2, $cases);
        self::assertSame('2', SubscribeTemplateType::ONCE->value);
        self::assertSame('3', SubscribeTemplateType::LONG->value);
    }

    public function testGetLabelReturnsCorrectLabels(): void
    {
        self::assertSame('一次性订阅', SubscribeTemplateType::ONCE->getLabel());
        self::assertSame('长期订阅', SubscribeTemplateType::LONG->getLabel());
    }

    public function testToArrayReturnsCorrectArray(): void
    {
        $onceArray = SubscribeTemplateType::ONCE->toArray();

        self::assertIsArray($onceArray);
        self::assertCount(2, $onceArray);
        self::assertArrayHasKey('value', $onceArray);
        self::assertArrayHasKey('label', $onceArray);
        self::assertSame('2', $onceArray['value']);
        self::assertSame('一次性订阅', $onceArray['label']);

        $longArray = SubscribeTemplateType::LONG->toArray();
        self::assertSame('3', $longArray['value']);
        self::assertSame('长期订阅', $longArray['label']);
    }

    public function testToSelectItemReturnsCorrectFormat(): void
    {
        $onceItem = SubscribeTemplateType::ONCE->toSelectItem();

        self::assertIsArray($onceItem);
        self::assertArrayHasKey('value', $onceItem);
        self::assertArrayHasKey('label', $onceItem);
        self::assertArrayHasKey('text', $onceItem);
        self::assertArrayHasKey('name', $onceItem);

        self::assertSame('2', $onceItem['value']);
        self::assertSame('一次性订阅', $onceItem['label']);
        self::assertSame('一次性订阅', $onceItem['text']);
        self::assertSame('一次性订阅', $onceItem['name']);

        $longItem = SubscribeTemplateType::LONG->toSelectItem();
        self::assertSame('3', $longItem['value']);
        self::assertSame('长期订阅', $longItem['label']);
    }

    public function testGenOptionsReturnsAllItems(): void
    {
        $options = SubscribeTemplateType::genOptions();

        self::assertIsArray($options);
        self::assertCount(2, $options);

        foreach ($options as $option) {
            self::assertIsArray($option);
            self::assertArrayHasKey('value', $option);
            self::assertArrayHasKey('label', $option);
            self::assertArrayHasKey('text', $option);
            self::assertArrayHasKey('name', $option);
        }

        $onceOption = null;
        foreach ($options as $option) {
            if ('2' === $option['value']) {
                $onceOption = $option;
                break;
            }
        }

        self::assertNotNull($onceOption);
        self::assertSame('2', $onceOption['value']);
        self::assertSame('一次性订阅', $onceOption['label']);
    }

    public function testValueUniqueness(): void
    {
        $values = [];
        foreach (SubscribeTemplateType::cases() as $case) {
            $value = $case->value;
            self::assertNotContains($value, $values, "Duplicate value found: {$value}");
            $values[] = $value;
        }
    }

    public function testLabelUniqueness(): void
    {
        $labels = [];
        foreach (SubscribeTemplateType::cases() as $case) {
            $label = $case->getLabel();
            self::assertNotContains($label, $labels, "Duplicate label found: {$label}");
            $labels[] = $label;
        }
    }
}
