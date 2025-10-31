<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

#[ORM\Table(name: 'ims_wxapp_subscribe_template_entity', options: ['comment' => '微信订阅消息模板库'])]
#[ORM\Entity(repositoryClass: SubscribeTemplateRepository::class)]
class SubscribeTemplate implements \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否有效'])]
    #[TrackColumn]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '模板ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $priTmplId = null;

    #[ORM\Column(type: Types::STRING, length: 255, options: ['comment' => '模板标题'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[ORM\Column(enumType: SubscribeTemplateType::class, options: ['comment' => '模板类型'])]
    #[Assert\NotNull]
    #[Assert\Choice(callback: [SubscribeTemplateType::class, 'cases'])]
    private ?SubscribeTemplateType $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '模板内容'])]
    #[Assert\Length(max: 65535)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '模板内容示例'])]
    #[Assert\Length(max: 65535)]
    private ?string $example = null;

    /**
     * @var Collection<int, SubscribeParam>
     */
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

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getPriTmplId(): ?string
    {
        return $this->priTmplId;
    }

    public function setPriTmplId(string $priTmplId): void
    {
        $this->priTmplId = $priTmplId;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function setExample(?string $example): void
    {
        $this->example = $example;
    }

    public function getType(): ?SubscribeTemplateType
    {
        return $this->type;
    }

    public function setType(SubscribeTemplateType $type): void
    {
        $this->type = $type;
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
