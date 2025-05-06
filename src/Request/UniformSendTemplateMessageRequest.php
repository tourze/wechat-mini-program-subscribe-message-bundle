<?php

namespace WechatMiniProgramSubscribeMessageBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 下发统一消息
 * 该接口用于下发小程序和公众号统一的服务消息。
 * 目前很少使用这个接口了
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/uniform-message/sendUniformMessage.html
 */
class UniformSendTemplateMessageRequest extends WithAccountRequest
{
    /**
     * @var string 用户openid，可以是小程序的openid，也可以是mp_template_msg.appid对应的公众号的openid
     */
    private string $toUser;

    /**
     * @var MpTemplateMsg|null 公众号模板消息相关的信息，可以参考公众号模板消息接口；有此节点并且没有weapp_template_msg节点时，发送公众号模板消息
     */
    private ?MpTemplateMsg $mpTemplateMsg = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/message/wxopen/template/uniform_send';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'touser' => $this->getToUser(),
        ];
        if (null !== $this->getMpTemplateMsg()) {
            $json['mp_template_msg'] = [
                'appid' => $this->getMpTemplateMsg()->getAppId(),
                'template_id' => $this->getMpTemplateMsg()->getTemplateId(),
                'url' => $this->getMpTemplateMsg()->getUrl(),
                'miniprogram' => $this->getMpTemplateMsg()->getMiniprogram(),
                'data' => $this->getMpTemplateMsg()->getData(),
            ];
        }

        return [
            'json' => $json,
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

    public function getMpTemplateMsg(): ?MpTemplateMsg
    {
        return $this->mpTemplateMsg;
    }

    public function setMpTemplateMsg(?MpTemplateMsg $mpTemplateMsg): void
    {
        $this->mpTemplateMsg = $mpTemplateMsg;
    }
}
