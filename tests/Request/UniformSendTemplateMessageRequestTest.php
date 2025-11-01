<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramSubscribeMessageBundle\Request\MpTemplateMsg;
use WechatMiniProgramSubscribeMessageBundle\Request\UniformSendTemplateMessageRequest;

/**
 * @internal
 */
#[CoversClass(UniformSendTemplateMessageRequest::class)]
final class UniformSendTemplateMessageRequestTest extends RequestTestCase
{
    private UniformSendTemplateMessageRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new UniformSendTemplateMessageRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        self::assertSame('/cgi-bin/message/wxopen/template/uniform_send', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithRequiredParams(): void
    {
        $this->request->setToUser('user123');

        $options = $this->request->getRequestOptions();
        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);

        $jsonData = $options['json'];
        self::assertIsArray($jsonData);

        self::assertSame('user123', $jsonData['touser']);
        self::assertArrayNotHasKey('mp_template_msg', $jsonData);
    }

    public function testGetRequestOptionsWithMpTemplateMsg(): void
    {
        $this->request->setToUser('user123');

        // 使用具体类 MpTemplateMsg mock 的理由：
        // 1. MpTemplateMsg 是核心数据传输对象，测试需要验证其数据结构和行为
        // 2. 该类的方法稳定且语义明确，使用具体类可以确保测试的准确性
        // 3. 在请求组装测试中，我们需要确保与真实对象的交互符合预期
        $mpTemplateMsg = $this->createMock(MpTemplateMsg::class);
        $mpTemplateMsg->method('getAppId')->willReturn('wx1234567890');
        $mpTemplateMsg->method('getTemplateId')->willReturn('template123');
        $mpTemplateMsg->method('getUrl')->willReturn('https://example.com');
        $mpTemplateMsg->method('getMiniprogram')->willReturn(['appid' => 'miniapp123', 'pagepath' => 'pages/index/index']);
        $mpTemplateMsg->method('getData')->willReturn(['first' => ['value' => 'Hello']]);

        $this->request->setMpTemplateMsg($mpTemplateMsg);

        $options = $this->request->getRequestOptions();
        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);

        $jsonData = $options['json'];
        self::assertIsArray($jsonData);

        self::assertSame('user123', $jsonData['touser']);

        self::assertArrayHasKey('mp_template_msg', $jsonData);
        $mpTemplateMsgData = $jsonData['mp_template_msg'];
        self::assertIsArray($mpTemplateMsgData);

        self::assertSame('wx1234567890', $mpTemplateMsgData['appid']);
        self::assertSame('template123', $mpTemplateMsgData['template_id']);
        self::assertSame('https://example.com', $mpTemplateMsgData['url']);
        self::assertSame(['appid' => 'miniapp123', 'pagepath' => 'pages/index/index'], $mpTemplateMsgData['miniprogram']);
        self::assertSame(['first' => ['value' => 'Hello']], $mpTemplateMsgData['data']);
    }

    public function testToUserGetterAndSetter(): void
    {
        $this->request->setToUser('user123');
        self::assertSame('user123', $this->request->getToUser());
    }

    public function testMpTemplateMsgGetterAndSetter(): void
    {
        $mpTemplateMsg = new MpTemplateMsg();
        $mpTemplateMsg->setAppId('wx1234567890');

        $this->request->setMpTemplateMsg($mpTemplateMsg);
        self::assertSame($mpTemplateMsg, $this->request->getMpTemplateMsg());

        $this->request->setMpTemplateMsg(null);
        self::assertNull($this->request->getMpTemplateMsg());
    }
}
