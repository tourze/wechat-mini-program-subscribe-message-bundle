<?php

declare(strict_types=1);

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramSubscribeMessageBundle\Service\TemplateDataValidator;

#[CoversClass(TemplateDataValidator::class)]
#[RunTestsInSeparateProcesses]
class TemplateDataValidatorTest extends AbstractIntegrationTestCase
{
    private TemplateDataValidator $validator;

    protected function onSetUp(): void
    {
        $validator = self::getContainer()->get(TemplateDataValidator::class);
        self::assertInstanceOf(TemplateDataValidator::class, $validator);
        $this->validator = $validator;
    }

    public function testValidateTemplateDatumWithValidData(): void
    {
        $validDatum = [
            'priTmplId' => 'test123',
            'title' => 'Test Template',
            'content' => 'Test content',
        ];

        $result = $this->validator->validateTemplateDatum($validDatum);

        $this->assertIsArray($result);
        $this->assertSame($validDatum, $result);
    }

    public function testValidateTemplateDatumWithInvalidData(): void
    {
        $result = $this->validator->validateTemplateDatum('invalid');

        $this->assertFalse($result);
    }

    public function testValidateEnumDatumWithValidData(): void
    {
        $validEnumDatum = [
            'keywordCode' => 'test.DATA',
            'enumValue' => 'value1',
        ];

        $result = $this->validator->validateEnumDatum($validEnumDatum);

        $this->assertIsArray($result);
        $this->assertSame($validEnumDatum, $result);
    }

    public function testValidateEnumDatumWithInvalidData(): void
    {
        $result = $this->validator->validateEnumDatum('invalid');

        $this->assertFalse($result);
    }

    public function testHasValidEnumValueListWithValidData(): void
    {
        $datum = [
            'keywordEnumValueList' => [
                ['keywordCode' => 'test.DATA'],
            ],
        ];

        $result = $this->validator->hasValidEnumValueList($datum);

        $this->assertTrue($result);
    }

    public function testHasValidEnumValueListWithInvalidData(): void
    {
        $datum = [
            'keywordEnumValueList' => [],
        ];

        $result = $this->validator->hasValidEnumValueList($datum);

        $this->assertFalse($result);
    }
}
