<?php

namespace WechatMiniProgramSubscribeMessageBundle\Command;

use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeTemplateSyncService;

#[AsCronTask(expression: '15 */4 * * *')]
#[AsCommand(name: self::NAME, description: '定期同步订阅消息模板到本地')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class SyncSubscribeTemplateCommand extends Command
{
    public const NAME = 'wechat-mini-program:sync-subscribe-template';

    public function __construct(
        private readonly SubscribeTemplateSyncService $syncService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->syncService->syncAllAccounts();

        return Command::SUCCESS;
    }
}
