<?php

declare(strict_types=1);

namespace WechatMiniProgramSubscribeMessageBundle\Service;

use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;

/**
 * 模板数据验证帮助类
 */
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class TemplateDataValidator
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param mixed $datum
     * @return array<string, mixed>|false
     */
    public function validateTemplateDatum(mixed $datum): array|false
    {
        if (!is_array($datum)) {
            $this->logger->warning('模板数据项格式不正确', [
                'datum_type' => gettype($datum),
                'datum' => $datum,
            ]);
            return false;
        }

        // 验证数组包含需要的字符串键
        if (!$this->hasStringKeys($datum)) {
            $this->logger->warning('模板数据项缺少字符串键', [
                'datum' => $datum,
            ]);
            return false;
        }

        return $this->normalizeArrayKeys($datum);
    }

    /**
     * @param mixed $enumDatum
     * @return array<string, mixed>|false
     */
    public function validateEnumDatum(mixed $enumDatum): array|false
    {
        if (!is_array($enumDatum)) {
            $this->logger->warning('枚举数据项格式不正确', [
                'enumDatum_type' => gettype($enumDatum),
                'enumDatum' => $enumDatum,
            ]);
            return false;
        }

        // 验证数组包含需要的字符串键
        if (!$this->hasStringKeys($enumDatum)) {
            $this->logger->warning('枚举数据项缺少字符串键', [
                'enumDatum' => $enumDatum,
            ]);
            return false;
        }

        return $this->normalizeArrayKeys($enumDatum);
    }

    /**
     * @param array<string, mixed> $datum
     */
    public function hasValidEnumValueList(array $datum): bool
    {
        return isset($datum['keywordEnumValueList'])
            && is_array($datum['keywordEnumValueList'])
            && [] !== $datum['keywordEnumValueList'];
    }

    /**
     * @param array<mixed> $array
     */
    private function hasStringKeys(array $array): bool
    {
        foreach (array_keys($array) as $key) {
            if (is_string($key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array<mixed> $array
     * @return array<string, mixed>
     */
    private function normalizeArrayKeys(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $stringKey = is_string($key) ? $key : (string) $key;
            $result[$stringKey] = $value;
        }
        return $result;
    }
}
