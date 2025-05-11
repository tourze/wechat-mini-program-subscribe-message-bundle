<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\GetCategoryListRequest;

class GetCategoryListRequestTest extends TestCase
{
    private GetCategoryListRequest $request;
    
    protected function setUp(): void
    {
        $this->request = new GetCategoryListRequest();
    }
    
    public function testGetRequestPath_returnsCorrectPath()
    {
        $this->assertSame('/wxaapi/newtmpl/getcategory', $this->request->getRequestPath());
    }
    
    public function testGetRequestOptions_returnsEmptyJsonArray()
    {
        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertEmpty($options['json']);
    }
    
    public function testGetRequestMethod_returnsGet()
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }
}