<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\EasyAdmin\Attribute\Action\BatchDeletable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use Tourze\ScheduleEntityCleanBundle\Attribute\AsScheduleClean;
use WechatMiniProgramAuthBundle\Entity\User;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

#[AsScheduleClean(expression: '4 4 * * *', defaultKeepDay: 365, keepDayEnv: 'WECHAT_MINI_PROGRAM_SUBSCRIBE_MESSAGE_PERSIST_DAY')]
#[AsPermission(title: '订阅结果日志')]
#[Deletable]
#[BatchDeletable]
#[ORM\Entity(repositoryClass: SubscribeMessageLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_subscribe_message_log', options: ['comment' => '订阅结果日志'])]
class SubscribeMessageLog
{
    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ListColumn(title: '小程序')]
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ListColumn(title: '用户')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?User $user = null;

    #[IndexColumn]
    #[Filterable]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 100, options: ['comment' => '模板ID'])]
    private ?string $templateId = null;

    #[IndexColumn]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '订阅结果'])]
    private ?string $subscribeStatus = null;

    #[Keyword]
    #[ListColumn(width: 400)]
    #[ORM\Column(type: Types::TEXT, options: ['comment' => '原始数据'])]
    private ?string $rawData = null;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '发送结果MsgId'])]
    private ?string $resultMsgId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '发送结果ErrorCode'])]
    private ?int $resultCode = null;

    #[ORM\Column(type: Types::STRING, length: 36, nullable: true, options: ['comment' => '发送结果ErrorStatus'])]
    private ?string $resultStatus = null;

    #[CreateIpColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '创建者IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '更新者IP'])]
    private ?string $updatedFromIp = null;

    public function getId(): ?string
    {
        return $this->id;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
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
}
