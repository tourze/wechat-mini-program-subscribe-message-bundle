<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\GetCategoryListRequest;

/**
 * @internal
 */
#[CoversClass(GetCategoryListRequest::class)]
final class GetCategoryListRequestTest extends RequestTestCase
{
    private GetCategoryListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GetCategoryListRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        self::assertSame('/wxaapi/newtmpl/getcategory', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsReturnsEmptyJsonArray(): void
    {
        $options = $this->request->getRequestOptions();
        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);
        self::assertEmpty($options['json']);
    }

    public function testGetRequestMethodReturnsGet(): void
    {
        self::assertSame('GET', $this->request->getRequestMethod());
    }
}
