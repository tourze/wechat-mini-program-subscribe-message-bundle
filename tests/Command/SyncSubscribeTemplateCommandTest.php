<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramSubscribeMessageBundle\Command\SyncSubscribeTemplateCommand;

/**
 * @internal
 */
#[CoversClass(SyncSubscribeTemplateCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncSubscribeTemplateCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncSubscribeTemplateCommand::class);

        return new CommandTester($command);
    }

    public function testExecuteWithValidResponse(): void
    {
        $tester = $this->getCommandTester();
        $statusCode = $tester->execute([]);
        $this->assertEquals(0, $statusCode);
    }
}
