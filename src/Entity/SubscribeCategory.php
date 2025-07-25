<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;

#[ORM\Table(name: 'wechat_mini_program_subscribe_category', options: ['comment' => '微信订阅消息类目'])]
#[ORM\Entity(repositoryClass: SubscribeCategoryRepository::class)]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_subscribe_category_idx_uniq', columns: ['account_id', 'category_id'])]
class SubscribeCategory implements Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(options: ['comment' => '类目ID'])]
    private ?int $categoryId = null;

#[ORM\Column(length: 60, options: ['comment' => '字段说明'])]
    private ?string $name = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
