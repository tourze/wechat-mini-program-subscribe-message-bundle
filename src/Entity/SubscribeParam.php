<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Action\Editable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;

#[AsPermission(title: '订阅消息数据参数')]
#[Editable]
#[ORM\Table(name: 'wechat_mini_program_subscribe_param', options: ['comment' => '订阅消息数据参数'])]
#[ORM\Entity(repositoryClass: SubscribeParamRepository::class)]
#[ORM\UniqueConstraint(name: 'wechat_mini_program_subscribe_param_idx_uniq', columns: ['template_id', 'code'])]
class SubscribeParam implements \Stringable
{
    use TimestampableAware;

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'params')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubscribeTemplate $template = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 40, enumType: SubscribeTemplateData::class, options: ['comment' => '数据类型'])]
    private ?SubscribeTemplateData $type = null;

    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 40, options: ['comment' => '数据key'])]
    private ?string $code = null;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '数据映射表达式'])]
    private ?string $mapExpression = null;

    #[FormField]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '枚举数据'])]
    private array $enumValues = [];

    public function __toString(): string
    {
        if (!$this->getId()) {
            return '';
        }

        return $this->getCode();
    }

    public function getId(): ?string
    {
        return $this->id;
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
