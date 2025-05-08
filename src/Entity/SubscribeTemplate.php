<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\EasyAdmin\Attribute\Action\CurdAction;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

#[AsPermission(title: '微信订阅消息模板库')]
#[ORM\Table(name: 'ims_wxapp_subscribe_template_entity', options: ['comment' => '微信订阅消息模板库'])]
#[ORM\Entity(repositoryClass: SubscribeTemplateRepository::class)]
class SubscribeTemplate
{
    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
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
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
    private ?bool $valid = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, unique: true, options: ['comment' => '模板id'])]
    private ?string $priTmplId = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 40, options: ['comment' => '模版标题'])]
    private ?string $title = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, enumType: SubscribeTemplateType::class, options: ['comment' => '模版类型'])]
    private ?SubscribeTemplateType $type = null;

    #[ListColumn(width: 250)]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '模版内容'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '模板内容示例'])]
    private ?string $example = null;

    #[CurdAction(label: '参数配置')]
    #[ORM\OneToMany(mappedBy: 'template', targetEntity: SubscribeParam::class, orphanRemoval: true)]
    private Collection $params;

    public function __construct()
    {
        $this->params = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getPriTmplId(): ?string
    {
        return $this->priTmplId;
    }

    public function setPriTmplId(string $priTmplId): self
    {
        $this->priTmplId = $priTmplId;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(?string $example): self
    {
        $this->example = $example;

        return $this;
    }

    public function getType(): ?SubscribeTemplateType
    {
        return $this->type;
    }

    public function setType(SubscribeTemplateType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, SubscribeParam>
     */
    public function getParams(): Collection
    {
        return $this->params;
    }

    public function addParam(SubscribeParam $param): self
    {
        if (!$this->params->contains($param)) {
            $this->params->add($param);
            $param->setTemplate($this);
        }

        return $this;
    }

    public function removeParam(SubscribeParam $param): self
    {
        if ($this->params->removeElement($param)) {
            // set the owning side to null (unless already changed)
            if ($param->getTemplate() === $this) {
                $param->setTemplate(null);
            }
        }

        return $this;
    }
}
