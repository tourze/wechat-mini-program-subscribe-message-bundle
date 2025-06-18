<?php

namespace WechatMiniProgramSubscribeMessageBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use WechatMiniProgramServerMessageBundle\Event\ServerMessageRequestEvent;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Json\Json;

/**
 * 消费者在进行订阅相关操作时，微信会发送通知给我们后端。
 * 后端需要记录下该数据日志，同时分发相关事件出去。
 */
class SubscribeMessageServerSubscriber
{
    public function __construct(
        private readonly SubscribeMessageLogRepository $messageLogRepository,
        private readonly DoctrineService $doctrineService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 当用户触发订阅消息弹框后,用户的相关行为事件结果会推送至开发者所配置的服务器地址
     *
     * @throws \JsonException
     */
    #[AsEventListener(priority: 10)]
    public function onPopupCallback(ServerMessageRequestEvent $event): void
    {
        $Event = ArrayHelper::getValue($event->getMessage(), 'Event');
        if ('subscribe_msg_popup_event' !== $Event) {
            return;
        }

        // {
        //    "ToUserName": "gh_6b8d87e0a0bd",
        //    "FromUserName": "oEAYS5SphcwGXgoCSmO7C0Zw4uF0",
        //    "CreateTime": 1671439759,
        //    "MsgType": "event",
        //    "Event": "subscribe_msg_popup_event",
        //    "List": {
        //        "PopupScene": "0",
        //        "SubscribeStatusString": "accept",
        //        "TemplateId": "FLp-6P7-YQFFKtz9RroJLOJlD1zMFTH0ASRqDILpYys"
        //    }
        // }

        // 保存订阅日志
        $list = $event->getMessage()['SubscribeMsgPopupEvent'] ?? $event->getMessage();
        if ((bool) isset($list['List'])) {
            $list = $list['List'];
        }
        // 兼容
        if ((bool) isset($list['TemplateId'])) {
            $list = [$list];
        }

        foreach ($list as $item) {
            $TemplateId = ArrayHelper::getValue($item, 'TemplateId');
            if (empty($TemplateId)) {
                $this->logger->error('微信返回异常的订阅结果数据', [
                    'item' => $item,
                    'message' => $event->getMessage(),
                ]);
                continue;
            }

            // 拆分为N个记录，方便我们一个个发送
            $log = new SubscribeMessageLog();
            $log->setAccount($event->getAccount());
            $log->setUser($event->getWechatUser());
            $log->setTemplateId($TemplateId);
            $log->setSubscribeStatus($item['SubscribeStatusString']);
            $log->setRawData(Json::encode($item));
            $this->doctrineService->asyncInsert($log);

            // 这里新增一个事件
            $nextEvent = new SubscribeMsgPopupEvent();
            $nextEvent->setAccount($event->getAccount());
            $nextEvent->setTemplateId($TemplateId);
            $nextEvent->setUser($event->getWechatUser());
            $nextEvent->setSubscribeStatus($item['SubscribeStatusString']);
            $this->eventDispatcher->dispatch($nextEvent);
        }
    }

    /**
     * 当用户在手机端服务通知里消息卡片右上角“...”管理消息时,或者在小程序设置管理中的订阅消息管理页面内管理消息时,相应的行为事件会推送至开发者所配置的服务器地址。
     * 目前只推送取消订阅的事件，即对消息设置“拒收”
     */
    #[AsEventListener(priority: 10)]
    public function onManageCallback(ServerMessageRequestEvent $event): void
    {
        $Event = ArrayHelper::getValue($event->getMessage(), 'Event');
        if ('subscribe_msg_change_event' !== $Event) {
            return;
        }

        // 保存订阅日志
        // 格式参考 {"message":"收到小程序服务端消息","context":{"ToUserName":"gh_262ad44747a1","FromUserName":"ovGLy5FzO5xf77JAmEPVYrIKI5ro","CreateTime":"1665117240","MsgType":"event","Event":"subscribe_msg_change_event","SubscribeMsgChangeEvent":{"List":[{"TemplateId":"lPirPE98MMSKYP2ymM9LC0zs_pZAuPTI2gZlR3m00jo","SubscribeStatusString":"reject"},{"TemplateId":"-ZQBhqY_fHOR5gpd-30A1j16OBpvh9Gy-vUsm1bQ4j4","SubscribeStatusString":"reject"},{"TemplateId":"rYe-NJDGnFM8fjlM7QEZL4wzAf9acnJ_69rhlvoWz48","SubscribeStatusString":"reject"},{"TemplateId":"3yUt4oxBI9QsUp0gcyEVLYXgulEmK3vyUPX25Ejr8og","SubscribeStatusString":"reject"}]}},"level":200,"level_name":"INFO","channel":"app","datetime":"2022-10-07T12:34:00.777594+08:00","extra":{"request_id":"fc1b3dd4-0ce5-478a-8009-3c66cb62f399"}}
        // 格式参考 {"ToUserName":"gh_262ad44747a1","FromUserName":"ovGLy5OaLS-nFVNsH5FmvIbPS_kY","CreateTime":"1665536678","MsgType":"event","Event":"subscribe_msg_change_event","SubscribeMsgChangeEvent":{"List":{"TemplateId":"FktTRzgkSHHSBEPsSumxbUrlhSak4vGmc7wmhLTSDyE","SubscribeStatusString":"reject"}}}
        // 要注意，这里的List不固定的，偶尔是数组，偶尔是对象
        $list = $event->getMessage()['SubscribeMsgChangeEvent'];
        if ((bool) isset($list['List'])) {
            $list = $list['List'];
        }

        if ((bool) isset($list['TemplateId'])) {
            $list = [$list];
        }

        foreach ($list as $item) {
            // 拆分为N个记录，方便我们一个个发送
            $log = new SubscribeMessageLog();
            $log->setAccount($event->getAccount());
            $log->setUser($event->getWechatUser());
            $log->setTemplateId($item['TemplateId']);
            $log->setSubscribeStatus($item['SubscribeStatusString']);
            $log->setRawData(Json::encode($item));
            $this->doctrineService->asyncInsert($log);
        }
    }

    /**
     * 调用订阅消息接口发送消息给用户的最终结果,会推送下发结果事件至开发者所配置的服务器地址。
     * 失败仅包含因异步推送导致的系统失败
     *
     * @see https://developers.weixin.qq.com/doc/offiaccount/Subscription_Messages/api.html
     */
    #[AsEventListener(priority: 10)]
    public function onResultCallback(ServerMessageRequestEvent $event): void
    {
        $Event = ArrayHelper::getValue($event->getMessage(), 'Event');
        if ('subscribe_msg_sent_event' !== $Event) {
            return;
        }

        // 查找一条有效的LOG
        $log = $this->messageLogRepository->findOneBy([
            'user' => $event->getWechatUser(),
            'account' => $event->getAccount(),
            'templateId' => $event->getMessage()['SubscribeMsgSentEvent']['List']['TemplateId'],
            'subscribeStatus' => 'accept',
            'resultMsgId' => null,
        ]);
        if ($log === null) {
            // 没的话就自己造一条
            $log = new SubscribeMessageLog();
            $log->setUser($event->getWechatUser());
            $log->setAccount($event->getAccount());
            $log->setSubscribeStatus('accept'); // 能拿到发送结果回调，说明是授权过的了
            $log->setRawData(Json::encode($event->getMessage()));
        }

        $log->setTemplateId($event->getMessage()['SubscribeMsgSentEvent']['List']['TemplateId']);
        $log->setResultMsgId(strval($event->getMessage()['SubscribeMsgSentEvent']['List']['MsgID']));
        $log->setResultCode($event->getMessage()['SubscribeMsgSentEvent']['List']['ErrorCode']);
        $log->setResultStatus($event->getMessage()['SubscribeMsgSentEvent']['List']['ErrorStatus']);
        $this->doctrineService->asyncInsert($log);
    }
}
