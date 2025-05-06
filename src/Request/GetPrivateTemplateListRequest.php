<?php

namespace WechatMiniProgramSubscribeMessageBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 获取个人模板列表
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/getMessageTemplateList.html
 */
class GetPrivateTemplateListRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/wxaapi/newtmpl/gettemplate';
    }

    public function getRequestMethod(): ?string
    {
        return 'GET';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [],
        ];
    }
}
