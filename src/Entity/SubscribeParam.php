<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;

#[ORM\Table(name: 'wechat_mini_program_subscribe_param', options: ['comment' => '订阅消息数据参数'])]
#[ORM\Entity(repositoryClass: SubscribeParamRepository::class)]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_subscribe_param_idx_uniq', columns: ['template_id', 'code'])]
class SubscribeParam implements \Stringable
{
    use TimestampableAware;
    use SnowflakeKeyAware;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'params')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubscribeTemplate $template = null;

    private ?SubscribeTemplateData $type = null;

    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '数据映射表达式'])]
    private ?string $mapExpression = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '枚举数据'])]
    private array $enumValues = [];

    public function __toString(): string
    {
        if ($this->getId() === null) {
            return '';
        }

        return $this->getCode();
    }

    public function getTemplate(): ?SubscribeTemplate
    {
        return $this->template;
    }

    public function setTemplate(?SubscribeTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getType(): ?SubscribeTemplateData
    {
        return $this->type;
    }

    public function setType(SubscribeTemplateData $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEnumValues(): array
    {
        return $this->enumValues;
    }

    public function setEnumValues(?array $enumValues): self
    {
        $this->enumValues = $enumValues;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMapExpression(): ?string
    {
        return $this->mapExpression;
    }

    public function setMapExpression(?string $mapExpression): self
    {
        $this->mapExpression = $mapExpression;

        return $this;
    }
}
