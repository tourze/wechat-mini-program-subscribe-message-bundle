<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatMiniProgramSubscribeMessageBundle\Request\GetPrivateTemplateListRequest;

/**
 * @internal
 */
#[CoversClass(GetPrivateTemplateListRequest::class)]
final class GetPrivateTemplateListRequestTest extends RequestTestCase
{
    private GetPrivateTemplateListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new GetPrivateTemplateListRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        self::assertSame('/wxaapi/newtmpl/gettemplate', $this->request->getRequestPath());
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
