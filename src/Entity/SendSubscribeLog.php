<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SendSubscribeLogRepository;

/**
 * 发送订阅消息日志
 */
#[ORM\Entity(repositoryClass: SendSubscribeLogRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_send_subscribe_log', options: ['comment' => '表描述'])]
class SendSubscribeLog implements \Stringable
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

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '模板ID'])]
    #[IndexColumn]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $templateId = null;

    #[ORM\Column(type: Types::TEXT, options: ['comment' => '发送结果'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 65535)]
    private ?string $result = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '备注'])]
    #[Assert\Length(max: 255)]
    private ?string $remark = null;

    #[ORM\ManyToOne(targetEntity: SubscribeTemplate::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?SubscribeTemplate $subscribeTemplate = null;

    public function getTemplateId(): ?string
    {
        return $this->templateId;
    }

    public function setTemplateId(string $templateId): void
    {
        $this->templateId = $templateId;
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

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): void
    {
        $this->result = $result;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }

    public function getSubscribeTemplate(): ?SubscribeTemplate
    {
        return $this->subscribeTemplate;
    }

    public function setSubscribeTemplate(?SubscribeTemplate $subscribeTemplate): void
    {
        $this->subscribeTemplate = $subscribeTemplate;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
