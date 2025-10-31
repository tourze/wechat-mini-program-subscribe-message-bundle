<?php

namespace WechatMiniProgramSubscribeMessageBundle\EventSubscriber;

use Monolog\Attribute\WithMonologChannel;
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
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
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

        $list = $this->extractPopupEventList($event);
        if (null === $list) {
            return;
        }

        foreach ($list as $item) {
            $this->processPopupEventItem($event, $item);
        }
    }

    /**
     * @return array<mixed>|null
     */
    private function extractPopupEventList(ServerMessageRequestEvent $event): ?array
    {
        $message = $event->getMessage();
        $list = $message['SubscribeMsgPopupEvent'] ?? $message;

        if (is_array($list) && isset($list['List'])) {
            $list = $list['List'];
        }

        if (is_array($list) && isset($list['TemplateId'])) {
            $list = [$list];
        }

        if (!is_array($list)) {
            $this->logger->error('微信返回的List数据不是数组格式', ['list' => $list, 'message' => $message]);

            return null;
        }

        return $list;
    }

    private function processPopupEventItem(ServerMessageRequestEvent $event, mixed $item): void
    {
        if (!$this->isValidPopupEventItem($item)) {
            return;
        }

        /** @var array<string, mixed>|object $item */
        $TemplateId = ArrayHelper::getValue($item, 'TemplateId');
        if (!$this->isValidTemplateId($TemplateId, $item, $event)) {
            return;
        }

        $subscribeStatus = $this->extractSubscribeStatus($item);
        $templateIdString = $this->convertToString($TemplateId);

        $this->createSubscribeLog($event, $templateIdString, $subscribeStatus, $item);
        $this->dispatchPopupEvent($event, $templateIdString, $subscribeStatus);
    }

    private function isValidPopupEventItem(mixed $item): bool
    {
        if (!is_array($item) && !is_object($item)) {
            $this->logger->error('微信返回的订阅项不是数组或对象格式', ['item' => $item]);

            return false;
        }

        return true;
    }

    private function isValidTemplateId(mixed $templateId, mixed $item, ServerMessageRequestEvent $event): bool
    {
        if (null === $templateId || '' === $templateId) {
            $this->logger->error('微信返回异常的订阅结果数据', [
                'item' => $item,
                'message' => $event->getMessage(),
            ]);

            return false;
        }

        return true;
    }

    private function extractSubscribeStatus(mixed $item): string
    {
        $subscribeStatusValue = is_array($item) && isset($item['SubscribeStatusString']) ? $item['SubscribeStatusString'] : '';

        return is_string($subscribeStatusValue) ? $subscribeStatusValue : (is_scalar($subscribeStatusValue) ? (string) $subscribeStatusValue : '');
    }

    private function convertToString(mixed $value): string
    {
        return is_string($value) ? $value : (is_scalar($value) ? (string) $value : '');
    }

    private function createSubscribeLog(ServerMessageRequestEvent $event, string $templateId, string $subscribeStatus, mixed $item): void
    {
        $log = new SubscribeMessageLog();
        $log->setAccount($event->getAccount());
        $log->setUser($event->getWechatUser());
        $log->setTemplateId($templateId);
        $log->setSubscribeStatus($subscribeStatus);
        $log->setRawData(Json::encode($item));
        $this->doctrineService->asyncInsert($log);
    }

    private function dispatchPopupEvent(ServerMessageRequestEvent $event, string $templateId, string $subscribeStatus): void
    {
        $nextEvent = new SubscribeMsgPopupEvent();
        $nextEvent->setAccount($event->getAccount());
        $nextEvent->setTemplateId($templateId);
        $nextEvent->setUser($event->getWechatUser());
        $nextEvent->setSubscribeStatus($subscribeStatus);
        $this->eventDispatcher->dispatch($nextEvent);
    }

    /**
     * 当用户在手机端服务通知里消息卡片右上角"..."管理消息时,或者在小程序设置管理中的订阅消息管理页面内管理消息时,相应的行为事件会推送至开发者所配置的服务器地址。
     * 目前只推送取消订阅的事件，即对消息设置"拒收"
     */
    #[AsEventListener(priority: 10)]
    public function onManageCallback(ServerMessageRequestEvent $event): void
    {
        $Event = ArrayHelper::getValue($event->getMessage(), 'Event');
        if ('subscribe_msg_change_event' !== $Event) {
            return;
        }

        $list = $this->extractChangeEventList($event);
        if (null === $list) {
            return;
        }

        foreach ($list as $item) {
            $this->processChangeEventItem($event, $item);
        }
    }

    /**
     * @return array<mixed>|null
     */
    private function extractChangeEventList(ServerMessageRequestEvent $event): ?array
    {
        $message = $event->getMessage();
        if (!isset($message['SubscribeMsgChangeEvent'])) {
            $this->logger->error('微信返回的消息格式不正确或缺少SubscribeMsgChangeEvent', ['message' => $message]);

            return null;
        }

        $list = $message['SubscribeMsgChangeEvent'];
        if (is_array($list) && isset($list['List'])) {
            $list = $list['List'];
        }

        if (is_array($list) && isset($list['TemplateId'])) {
            $list = [$list];
        }

        if (!is_array($list)) {
            $this->logger->error('微信返回的List数据不是数组格式', ['list' => $list, 'message' => $message]);

            return null;
        }

        return $list;
    }

    private function processChangeEventItem(ServerMessageRequestEvent $event, mixed $item): void
    {
        if (!is_array($item)) {
            $this->logger->error('微信返回的订阅项不是数组格式', ['item' => $item]);

            return;
        }

        $templateId = $item['TemplateId'] ?? '';
        $subscribeStatus = $item['SubscribeStatusString'] ?? '';

        if ('' === $templateId) {
            $this->logger->error('微信返回的TemplateId为空', ['item' => $item]);

            return;
        }

        $templateIdString = $this->convertToString($templateId);
        $subscribeStatusString = $this->convertToString($subscribeStatus);
        $this->createSubscribeLog($event, $templateIdString, $subscribeStatusString, $item);
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
        $message = $event->getMessage();
        if (!isset($message['SubscribeMsgSentEvent'])
            || !is_array($message['SubscribeMsgSentEvent'])
            || !isset($message['SubscribeMsgSentEvent']['List'])
            || !is_array($message['SubscribeMsgSentEvent']['List'])) {
            $this->logger->error('微信返回的消息格式不正确，缺少必要的SubscribeMsgSentEvent.List数据', ['message' => $message]);

            return;
        }

        $listData = $message['SubscribeMsgSentEvent']['List'];
        $templateId = $listData['TemplateId'] ?? '';

        if ('' === $templateId) {
            $this->logger->error('微信返回的TemplateId为空', ['listData' => $listData]);

            return;
        }

        $templateIdString = $this->convertToString($templateId);
        $log = $this->messageLogRepository->findOneBy([
            'user' => $event->getWechatUser(),
            'account' => $event->getAccount(),
            'templateId' => $templateIdString,
            'subscribeStatus' => 'accept',
            'resultMsgId' => null,
        ]);
        if (null === $log) {
            // 没的话就自己造一条
            $log = new SubscribeMessageLog();
            $log->setUser($event->getWechatUser());
            $log->setAccount($event->getAccount());
            $log->setSubscribeStatus('accept'); // 能拿到发送结果回调，说明是授权过的了
            $log->setRawData(Json::encode($message));
        }

        $log->setTemplateId($templateIdString);
        $log->setResultMsgId((string) ($listData['MsgID'] ?? ''));
        $log->setResultCode((int) ($listData['ErrorCode'] ?? 0));
        $log->setResultStatus((string) ($listData['ErrorStatus'] ?? ''));
        $this->doctrineService->asyncInsert($log);
    }
}
