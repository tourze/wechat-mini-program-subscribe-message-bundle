<?php

namespace WechatMiniProgramSubscribeMessageBundle\Request;

use WechatMiniProgramBundle\Enum\MiniProgramState;
use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 发送订阅消息
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/subscribe-message/subscribeMessage.send.html
 */
class SendSubscribeMessageRequest extends WithAccountRequest
{
    /**
     * @var string 接受消息的用户
     */
    private string $toUser;

    /**
     * @var string 所需下发的订阅模板id
     */
    private string $templateId;

    /** @var array<string, array<string, string>> */
    private array $data = [];

    /**
     * @var string|null 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
     */
    private ?string $page = null;

    /**
     * @var MiniProgramState|null 跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
     */
    private ?MiniProgramState $miniProgramState = null;

    /**
     * @var string|null 进入小程序查看”的语言类型，支持zh_CN(简体中文)、en_US(英文)、zh_HK(繁体中文)、zh_TW(繁体中文)，默认为zh_CN
     */
    private ?string $lang = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/message/subscribe/send';
    }

    public function getRequestOptions(): ?array
    {
        $arr = [
            'touser' => $this->getToUser(),
            'template_id' => $this->getTemplateId(),
            'data' => $this->getData(),
        ];

        if (null !== $this->getPage()) {
            $arr['page'] = $this->getPage();
        }

        if (null !== $this->getMiniProgramState()) {
            $arr['miniprogram_state'] = $this->getMiniProgramState()->value;
        }

        if (null !== $this->getLang()) {
            $arr['lang'] = $this->getLang();
        }

        return [
            'json' => $arr,
        ];
    }

    public function getToUser(): string
    {
        return $this->toUser;
    }

    public function setToUser(string $toUser): void
    {
        $this->toUser = $toUser;
    }

    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, array<string, string>> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): void
    {
        $this->page = $page;
    }

    public function getMiniProgramState(): ?MiniProgramState
    {
        return $this->miniProgramState;
    }

    public function setMiniProgramState(?MiniProgramState $miniProgramState): void
    {
        $this->miniProgramState = $miniProgramState;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): void
    {
        $this->lang = $lang;
    }
}
