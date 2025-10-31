<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\ScheduleEntityCleanBundle\Attribute\AsScheduleClean;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

#[AsScheduleClean(expression: '4 4 * * *', defaultKeepDay: 365, keepDayEnv: 'WECHAT_MINI_PROGRAM_SUBSCRIBE_MESSAGE_PERSIST_DAY')]
#[ORM\Entity(repositoryClass: SubscribeMessageLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_subscribe_message_log', options: ['comment' => '订阅结果日志'])]
class SubscribeMessageLog implements \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?UserInterface $user = null;

    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '模板ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $templateId = null;

    #[IndexColumn]
    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '订阅结果'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    private ?string $subscribeStatus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '原始数据'])]
    #[Assert\Length(max: 65535)]
    private ?string $rawData = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '发送结果MsgId'])]
    #[Assert\Length(max: 64)]
    private ?string $resultMsgId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送结果ErrorCode'])]
    #[Assert\Type(type: 'int')]
    private ?int $resultCode = null;

    #[ORM\Column(type: Types::STRING, length: 36, nullable: true, options: ['comment' => '发送结果ErrorStatus'])]
    #[Assert\Length(max: 36)]
    private ?string $resultStatus = null;

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): void
    {
        $this->rawData = $rawData;
    }

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getSubscribeStatus(): ?string
    {
        return $this->subscribeStatus;
    }

    public function setSubscribeStatus(string $subscribeStatus): void
    {
        $this->subscribeStatus = $subscribeStatus;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getResultMsgId(): ?string
    {
        return $this->resultMsgId;
    }

    public function setResultMsgId(?string $resultMsgId): void
    {
        $this->resultMsgId = $resultMsgId;
    }

    public function getResultCode(): ?int
    {
        return $this->resultCode;
    }

    public function setResultCode(?int $resultCode): void
    {
        $this->resultCode = $resultCode;
    }

    public function getResultStatus(): ?string
    {
        return $this->resultStatus;
    }

    public function setResultStatus(?string $resultStatus): void
    {
        $this->resultStatus = $resultStatus;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
