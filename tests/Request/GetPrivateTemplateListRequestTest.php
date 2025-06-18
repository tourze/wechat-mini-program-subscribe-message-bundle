<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\GetPrivateTemplateListRequest;

class GetPrivateTemplateListRequestTest extends TestCase
{
    private GetPrivateTemplateListRequest $request;
    
    protected function setUp(): void
    {
        $this->request = new GetPrivateTemplateListRequest();
    }
    
    public function testGetRequestPath_returnsCorrectPath()
    {
        $this->assertSame('/wxaapi/newtmpl/gettemplate', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_returnsEmptyJsonArray()
    {
        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertEmpty($options['json']);
    }
    
    public function testGetRequestMethod_returnsGet()
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }
} 