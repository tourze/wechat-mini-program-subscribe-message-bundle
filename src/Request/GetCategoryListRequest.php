<?php

namespace WechatMiniProgramSubscribeMessageBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 获取类目
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/getCategory.html
 */
class GetCategoryListRequest extends WithAccountRequest
{
    public function getRequestPath(): string
    {
        return '/wxaapi/newtmpl/getcategory';
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
