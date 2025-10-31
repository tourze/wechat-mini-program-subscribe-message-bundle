<?php

namespace WechatMiniProgramSubscribeMessageBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
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

    #[ORM\Column(enumType: SubscribeTemplateData::class, options: ['comment' => '参数类型'])]
    #[Assert\NotNull]
    #[Assert\Choice(callback: [SubscribeTemplateData::class, 'cases'])]
    private ?SubscribeTemplateData $type = null;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '参数代码'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '数据映射表达式'])]
    #[Assert\Length(max: 65535)]
    private ?string $mapExpression = null;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '枚举数据'])]
    #[Assert\Type(type: 'array')]
    private ?array $enumValues = null;

    public function __toString(): string
    {
        if (null === $this->getId()) {
            return '';
        }

        return $this->getCode() ?? '';
    }

    public function getTemplate(): ?SubscribeTemplate
    {
        return $this->template;
    }

    public function setTemplate(?SubscribeTemplate $template): void
    {
        $this->template = $template;
    }

    public function getType(): ?SubscribeTemplateData
    {
        return $this->type;
    }

    public function setType(SubscribeTemplateData $type): void
    {
        $this->type = $type;
    }

    /**
     * @return list<string>
     */
    public function getEnumValues(): array
    {
        if (null === $this->enumValues) {
            return [];
        }

        return array_values($this->enumValues);
    }

    /**
     * @param list<string>|null $enumValues
     */
    public function setEnumValues(?array $enumValues): void
    {
        $this->enumValues = $enumValues;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getMapExpression(): ?string
    {
        return $this->mapExpression;
    }

    public function setMapExpression(?string $mapExpression): void
    {
        $this->mapExpression = $mapExpression;
    }
}
