<?php

namespace WechatMiniProgramSubscribeMessageBundle\Request;

class MpTemplateMsg
{
    /**
     * @var string 公众号appid，要求与小程序有绑定且同主体
     */
    private string $appId;

    /**
     * @var string 公众号模板id
     */
    private string $templateId;

    /**
     * @var string 公众号模板消息所要跳转的url
     */
    private string $url;

    /**
     * @var array 小程序配置，如 {
     *            "appid":"xiaochengxuappid12345",
     *            "pagepath":"index?foo=bar"
     *            }
     */
    private array $miniprogram;

    /**
     * @var array 公众号模板消息的数据
     */
    private array $data;

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): void
    {
        $this->appId = $appId;
    }

    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getMiniprogram(): array
    {
        return $this->miniprogram;
    }

    public function setMiniprogram(array $miniprogram): void
    {
        $this->miniprogram = $miniprogram;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
