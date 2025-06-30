<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

#[ORM\Table(name: 'ims_wxapp_subscribe_template_entity', options: ['comment' => '微信订阅消息模板库'])]
#[ORM\Entity(repositoryClass: SubscribeTemplateRepository::class)]
class SubscribeTemplate implements Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[TrackColumn]
    private ?bool $valid = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    private ?string $priTmplId = null;

    private ?string $title = null;

    private ?SubscribeTemplateType $type = null;

    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '模板内容示例'])]
    private ?string $example = null;

    #[ORM\OneToMany(mappedBy: 'template', targetEntity: SubscribeParam::class, orphanRemoval: true)]
    private Collection $params;

    public function __construct()
    {
        $this->params = new ArrayCollection();
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

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
