<?php

namespace WechatMiniProgramSubscribeMessageBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;
use WechatMiniProgramSubscribeMessageBundle\Request\GetCategoryListRequest;

/**
 * 同步订阅消息目录
 */
#[AsCronTask('11 */2 * * *')]
#[AsCommand(name: self::NAME, description: '同步订阅消息目录')]
class SyncSubscribeCategoriesCommand extends Command
{
    public const NAME = 'wechat:mini-program:sync-subscribe-categories';
public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly SubscribeCategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            $request = new GetCategoryListRequest();
            $request->setAccount($account);
            try {
                $response = $this->client->request($request);
            } catch (\Throwable $exception) {
                $this->logger->error('获取订阅消息目录时发生错误', [
                    'account' => $account,
                    'exception' => $exception,
                ]);
                continue;
            }
            foreach ($response as $item) {
                if ((bool) is_array($item) && isset($item['id'])) {
                    $category = $this->categoryRepository->findOneBy([
                        'account' => $account,
                        'categoryId' => $item['id'],
                    ]);
                    if ($category === null) {
                        $category = new SubscribeCategory();
                        $category->setAccount($account);
                        $category->setCategoryId($item['id']);
                    }
                    $category->setName($item['name']);
                    $this->entityManager->persist($category);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}
