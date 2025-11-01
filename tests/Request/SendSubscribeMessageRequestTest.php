<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Enum\MiniProgramState;
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;

/**
 * @internal
 */
#[CoversClass(SendSubscribeMessageRequest::class)]
final class SendSubscribeMessageRequestTest extends RequestTestCase
{
    private SendSubscribeMessageRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new SendSubscribeMessageRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        self::assertSame('/cgi-bin/message/subscribe/send', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithRequiredParamsOnly(): void
    {
        $this->request->setToUser('user123');
        $this->request->setTemplateId('template123');
        $this->request->setData(['key1' => ['value' => 'value1']]);

        $options = $this->request->getRequestOptions();
        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);

        $jsonData = $options['json'];
        self::assertIsArray($jsonData);

        self::assertSame('user123', $jsonData['touser']);
        self::assertSame('template123', $jsonData['template_id']);
        self::assertSame(['key1' => ['value' => 'value1']], $jsonData['data']);
        self::assertArrayNotHasKey('page', $jsonData);
        self::assertArrayNotHasKey('miniprogram_state', $jsonData);
        self::assertArrayNotHasKey('lang', $jsonData);
    }

    public function testGetRequestOptionsWithAllParams(): void
    {
        $this->request->setToUser('user123');
        $this->request->setTemplateId('template123');
        $this->request->setData(['key1' => ['value' => 'value1']]);
        $this->request->setPage('pages/index/index?id=123');
        $this->request->setMiniProgramState(MiniProgramState::TRIAL);
        $this->request->setLang('en_US');

        $options = $this->request->getRequestOptions();
        self::assertIsArray($options);
        self::assertArrayHasKey('json', $options);

        $jsonData = $options['json'];
        self::assertIsArray($jsonData);

        self::assertSame('user123', $jsonData['touser']);
        self::assertSame('template123', $jsonData['template_id']);
        self::assertSame(['key1' => ['value' => 'value1']], $jsonData['data']);
        self::assertSame('pages/index/index?id=123', $jsonData['page']);
        self::assertSame('trial', $jsonData['miniprogram_state']);
        self::assertSame('en_US', $jsonData['lang']);
    }

    public function testToUserGetterAndSetter(): void
    {
        $this->request->setToUser('user123');
        self::assertSame('user123', $this->request->getToUser());
    }

    public function testTemplateIdGetterAndSetter(): void
    {
        $this->request->setTemplateId('template123');
        self::assertSame('template123', $this->request->getTemplateId());
    }

    public function testDataGetterAndSetter(): void
    {
        $data = ['key1' => ['value' => 'value1']];
        $this->request->setData($data);
        self::assertSame($data, $this->request->getData());
    }

    public function testPageGetterAndSetter(): void
    {
        $this->request->setPage('pages/index/index');
        self::assertSame('pages/index/index', $this->request->getPage());

        $this->request->setPage(null);
        self::assertNull($this->request->getPage());
    }

    public function testMiniProgramStateGetterAndSetter(): void
    {
        $state = MiniProgramState::TRIAL;
        $this->request->setMiniProgramState($state);
        self::assertSame($state, $this->request->getMiniProgramState());

        $this->request->setMiniProgramState(null);
        self::assertNull($this->request->getMiniProgramState());
    }

    public function testLangGetterAndSetter(): void
    {
        $this->request->setLang('en_US');
        self::assertSame('en_US', $this->request->getLang());

        $this->request->setLang(null);
        self::assertNull($this->request->getLang());
    }
}
