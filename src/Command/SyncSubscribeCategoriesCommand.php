<?php

namespace WechatMiniProgramSubscribeMessageBundle\Command;

use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeCategorySyncService;

/**
 * 同步订阅消息目录
 */
#[AsCronTask(expression: '11 */2 * * *')]
#[AsCommand(name: self::NAME, description: '同步订阅消息目录')]
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class SyncSubscribeCategoriesCommand extends Command
{
    public const NAME = 'wechat:mini-program:sync-subscribe-categories';

    public function __construct(
        private readonly SubscribeCategorySyncService $syncService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->syncService->syncAllAccounts();

        return Command::SUCCESS;
    }
}
