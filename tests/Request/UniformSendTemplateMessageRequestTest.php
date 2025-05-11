<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\MpTemplateMsg;
use WechatMiniProgramSubscribeMessageBundle\Request\UniformSendTemplateMessageRequest;

class UniformSendTemplateMessageRequestTest extends TestCase
{
    private UniformSendTemplateMessageRequest $request;
    
    protected function setUp(): void
    {
        $this->request = new UniformSendTemplateMessageRequest();
    }
    
    public function testGetRequestPath_returnsCorrectPath()
    {
        $this->assertSame('/cgi-bin/message/wxopen/template/uniform_send', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_withRequiredParams()
    {
        $this->request->setToUser('user123');
        
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertSame('user123', $options['json']['touser']);
        $this->assertArrayNotHasKey('mp_template_msg', $options['json']);
    }
    
    public function testGetRequestOptions_withMpTemplateMsg()
    {
        $this->request->setToUser('user123');
        
        $mpTemplateMsg = $this->createMock(MpTemplateMsg::class);
        $mpTemplateMsg->method('getAppId')->willReturn('wx1234567890');
        $mpTemplateMsg->method('getTemplateId')->willReturn('template123');
        $mpTemplateMsg->method('getUrl')->willReturn('https://example.com');
        $mpTemplateMsg->method('getMiniprogram')->willReturn(['appid' => 'miniapp123', 'pagepath' => 'pages/index/index']);
        $mpTemplateMsg->method('getData')->willReturn(['first' => ['value' => 'Hello']]);
        
        $this->request->setMpTemplateMsg($mpTemplateMsg);
        
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertSame('user123', $options['json']['touser']);
        $this->assertIsArray($options['json']['mp_template_msg']);
        $this->assertSame('wx1234567890', $options['json']['mp_template_msg']['appid']);
        $this->assertSame('template123', $options['json']['mp_template_msg']['template_id']);
        $this->assertSame('https://example.com', $options['json']['mp_template_msg']['url']);
        $this->assertSame(['appid' => 'miniapp123', 'pagepath' => 'pages/index/index'], $options['json']['mp_template_msg']['miniprogram']);
        $this->assertSame(['first' => ['value' => 'Hello']], $options['json']['mp_template_msg']['data']);
    }
    
    public function testToUserGetterAndSetter()
    {
        $this->request->setToUser('user123');
        $this->assertSame('user123', $this->request->getToUser());
    }
    
    public function testMpTemplateMsgGetterAndSetter()
    {
        $mpTemplateMsg = new MpTemplateMsg();
        $mpTemplateMsg->setAppId('wx1234567890');
        
        $this->request->setMpTemplateMsg($mpTemplateMsg);
        $this->assertSame($mpTemplateMsg, $this->request->getMpTemplateMsg());
        
        $this->request->setMpTemplateMsg(null);
        $this->assertNull($this->request->getMpTemplateMsg());
    }
} 