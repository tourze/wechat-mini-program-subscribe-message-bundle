<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;

class SubscribeTemplateTypeTest extends TestCase
{
    public function testEnum_hasExpectedCases()
    {
        $cases = SubscribeTemplateType::cases();
        
        $this->assertCount(2, $cases);
        $this->assertSame('2', SubscribeTemplateType::ONCE->value);
        $this->assertSame('3', SubscribeTemplateType::LONG->value);
    }
    
    public function testGetLabel_returnsCorrectLabels()
    {
        $this->assertSame('一次性订阅', SubscribeTemplateType::ONCE->getLabel());
        $this->assertSame('长期订阅', SubscribeTemplateType::LONG->getLabel());
    }
} 