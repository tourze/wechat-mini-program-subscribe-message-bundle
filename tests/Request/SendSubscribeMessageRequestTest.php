<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Enum\MiniProgramState;
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;

class SendSubscribeMessageRequestTest extends TestCase
{
    private SendSubscribeMessageRequest $request;
    
    protected function setUp(): void
    {
        $this->request = new SendSubscribeMessageRequest();
    }
    
    public function testGetRequestPath_returnsCorrectPath()
    {
        $this->assertSame('/cgi-bin/message/subscribe/send', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_withRequiredParamsOnly()
    {
        $this->request->setToUser('user123');
        $this->request->setTemplateId('template123');
        $this->request->setData(['key1' => ['value' => 'value1']]);
        
        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertSame('user123', $options['json']['touser']);
        $this->assertSame('template123', $options['json']['template_id']);
        $this->assertSame(['key1' => ['value' => 'value1']], $options['json']['data']);
        $this->assertArrayNotHasKey('page', $options['json']);
        $this->assertArrayNotHasKey('miniprogram_state', $options['json']);
        $this->assertArrayNotHasKey('lang', $options['json']);
    }
    
    public function testGetRequestOptions_withAllParams()
    {
        $this->request->setToUser('user123');
        $this->request->setTemplateId('template123');
        $this->request->setData(['key1' => ['value' => 'value1']]);
        $this->request->setPage('pages/index/index?id=123');
        $this->request->setMiniProgramState(MiniProgramState::TRIAL);
        $this->request->setLang('en_US');
        
        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertSame('user123', $options['json']['touser']);
        $this->assertSame('template123', $options['json']['template_id']);
        $this->assertSame(['key1' => ['value' => 'value1']], $options['json']['data']);
        $this->assertSame('pages/index/index?id=123', $options['json']['page']);
        $this->assertSame('trial', $options['json']['miniprogram_state']);
        $this->assertSame('en_US', $options['json']['lang']);
    }
    
    public function testToUserGetterAndSetter()
    {
        $this->request->setToUser('user123');
        $this->assertSame('user123', $this->request->getToUser());
    }
    
    public function testTemplateIdGetterAndSetter()
    {
        $this->request->setTemplateId('template123');
        $this->assertSame('template123', $this->request->getTemplateId());
    }
    
    public function testDataGetterAndSetter()
    {
        $data = ['key1' => ['value' => 'value1']];
        $this->request->setData($data);
        $this->assertSame($data, $this->request->getData());
    }
    
    public function testPageGetterAndSetter()
    {
        $this->request->setPage('pages/index/index');
        $this->assertSame('pages/index/index', $this->request->getPage());
        
        $this->request->setPage(null);
        $this->assertNull($this->request->getPage());
    }
    
    public function testMiniProgramStateGetterAndSetter()
    {
        $state = MiniProgramState::TRIAL;
        $this->request->setMiniProgramState($state);
        $this->assertSame($state, $this->request->getMiniProgramState());
        
        $this->request->setMiniProgramState(null);
        $this->assertNull($this->request->getMiniProgramState());
    }
    
    public function testLangGetterAndSetter()
    {
        $this->request->setLang('en_US');
        $this->assertSame('en_US', $this->request->getLang());
        
        $this->request->setLang(null);
        $this->assertNull($this->request->getLang());
    }
} 