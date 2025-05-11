<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\MpTemplateMsg;

class MpTemplateMsgTest extends TestCase
{
    private MpTemplateMsg $mpTemplateMsg;
    
    protected function setUp(): void
    {
        $this->mpTemplateMsg = new MpTemplateMsg();
    }
    
    public function testAppidGetterAndSetter()
    {
        $this->mpTemplateMsg->setAppId('wx1234567890');
        $this->assertSame('wx1234567890', $this->mpTemplateMsg->getAppId());
    }
    
    public function testTemplateIdGetterAndSetter()
    {
        $this->mpTemplateMsg->setTemplateId('template123');
        $this->assertSame('template123', $this->mpTemplateMsg->getTemplateId());
    }
    
    public function testUrlGetterAndSetter()
    {
        $this->mpTemplateMsg->setUrl('https://example.com');
        $this->assertSame('https://example.com', $this->mpTemplateMsg->getUrl());
    }
    
    public function testMiniprogramGetterAndSetter()
    {
        $miniprogram = ['appid' => 'miniapp123', 'pagepath' => 'pages/index/index'];
        $this->mpTemplateMsg->setMiniprogram($miniprogram);
        $this->assertSame($miniprogram, $this->mpTemplateMsg->getMiniprogram());
    }
    
    public function testDataGetterAndSetter()
    {
        $data = ['first' => ['value' => 'Hello'], 'keyword1' => ['value' => 'Content']];
        $this->mpTemplateMsg->setData($data);
        $this->assertSame($data, $this->mpTemplateMsg->getData());
    }
} 