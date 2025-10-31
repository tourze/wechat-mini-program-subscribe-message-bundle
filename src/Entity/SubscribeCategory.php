<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;

#[ORM\Table(name: 'wechat_mini_program_subscribe_category', options: ['comment' => '微信订阅消息类目'])]
#[ORM\Entity(repositoryClass: SubscribeCategoryRepository::class)]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_subscribe_category_idx_uniq', columns: ['account_id', 'category_id'])]
class SubscribeCategory implements \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(options: ['comment' => '类目ID'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $categoryId = null;

    #[ORM\Column(length: 60, options: ['comment' => '字段说明'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 60)]
    private ?string $name = null;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
