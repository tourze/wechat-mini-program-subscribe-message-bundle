<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\ScheduleEntityCleanBundle\Attribute\AsScheduleClean;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

#[AsScheduleClean(expression: '4 4 * * *', defaultKeepDay: 365, keepDayEnv: 'WECHAT_MINI_PROGRAM_SUBSCRIBE_MESSAGE_PERSIST_DAY')]
#[ORM\Entity(repositoryClass: SubscribeMessageLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_subscribe_message_log', options: ['comment' => '订阅结果日志'])]
class SubscribeMessageLog implements Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?UserInterface $user = null;

    #[IndexColumn]
    private ?string $templateId = null;

    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '订阅结果'])]
    private ?string $subscribeStatus = null;

    private ?string $rawData = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '发送结果MsgId'])]
    private ?string $resultMsgId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送结果ErrorCode'])]
    private ?int $resultCode = null;

    #[ORM\Column(type: Types::STRING, length: 36, nullable: true, options: ['comment' => '发送结果ErrorStatus'])]
    private ?string $resultStatus = null;

    #[CreateIpColumn]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    private ?string $updatedFromIp = null;

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): self
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    public function getSubscribeStatus(): ?string
    {
        return $this->subscribeStatus;
    }

    public function setSubscribeStatus(string $subscribeStatus): self
    {
        $this->subscribeStatus = $subscribeStatus;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getResultMsgId(): ?string
    {
        return $this->resultMsgId;
    }

    public function setResultMsgId(?string $resultMsgId): self
    {
        $this->resultMsgId = $resultMsgId;

        return $this;
    }

    public function getResultCode(): ?int
    {
        return $this->resultCode;
    }

    public function setResultCode(?int $resultCode): self
    {
        $this->resultCode = $resultCode;

        return $this;
    }

    public function getResultStatus(): ?string
    {
        return $this->resultStatus;
    }

    public function setResultStatus(?string $resultStatus): self
    {
        $this->resultStatus = $resultStatus;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->id;
    }
}
