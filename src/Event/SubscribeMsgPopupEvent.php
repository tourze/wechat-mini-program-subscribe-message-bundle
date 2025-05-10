<?php

namespace WechatMiniProgramSubscribeMessageBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

class SubscribeMsgPopupEvent extends Event
{
    private SubscribeMessageLog $log;

    private Account $account;

    private string $templateId;

    private UserInterface $user;

    private ?string $subscribeStatus;

    public function getLog(): SubscribeMessageLog
    {
        return $this->log;
    }

    public function setLog(SubscribeMessageLog $log): void
    {
        $this->log = $log;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getSubscribeStatus(): ?string
    {
        return $this->subscribeStatus;
    }

    public function setSubscribeStatus(?string $subscribeStatus): void
    {
        $this->subscribeStatus = $subscribeStatus;
    }
}
