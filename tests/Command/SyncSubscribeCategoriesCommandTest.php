<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramSubscribeMessageBundle\Command\SyncSubscribeCategoriesCommand;

/**
 * @internal
 */
#[CoversClass(SyncSubscribeCategoriesCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncSubscribeCategoriesCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getService(SyncSubscribeCategoriesCommand::class);

        return new CommandTester($command);
    }

    public function testExecuteWithValidAccounts(): void
    {
        $tester = $this->getCommandTester();
        $statusCode = $tester->execute([]);
        $this->assertEquals(0, $statusCode);
    }
}
