<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\MpTemplateMsg;

/**
 * @internal
 */
#[CoversClass(MpTemplateMsg::class)]
final class MpTemplateMsgTest extends TestCase
{
    private MpTemplateMsg $mpTemplateMsg;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mpTemplateMsg = new MpTemplateMsg();
    }

    public function testAppidGetterAndSetter(): void
    {
        $this->mpTemplateMsg->setAppId('wx1234567890');
        self::assertSame('wx1234567890', $this->mpTemplateMsg->getAppId());
    }

    public function testTemplateIdGetterAndSetter(): void
    {
        $this->mpTemplateMsg->setTemplateId('template123');
        self::assertSame('template123', $this->mpTemplateMsg->getTemplateId());
    }

    public function testUrlGetterAndSetter(): void
    {
        $this->mpTemplateMsg->setUrl('https://example.com');
        self::assertSame('https://example.com', $this->mpTemplateMsg->getUrl());
    }

    public function testMiniprogramGetterAndSetter(): void
    {
        $miniprogram = ['appid' => 'miniapp123', 'pagepath' => 'pages/index/index'];
        $this->mpTemplateMsg->setMiniprogram($miniprogram);
        self::assertSame($miniprogram, $this->mpTemplateMsg->getMiniprogram());
    }

    public function testDataGetterAndSetter(): void
    {
        $data = ['first' => ['value' => 'Hello'], 'keyword1' => ['value' => 'Content']];
        $this->mpTemplateMsg->setData($data);
        self::assertSame($data, $this->mpTemplateMsg->getData());
    }
}
