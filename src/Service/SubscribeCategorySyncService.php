<?php

namespace WechatMiniProgramSubscribeMessageBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;
use WechatMiniProgramSubscribeMessageBundle\Request\GetCategoryListRequest;

#[WithMonologChannel(channel: 'wechat_mini_program_subscribe_message')]
class SubscribeCategorySyncService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly Client $client,
        private readonly LoggerInterface $logger,
        private readonly SubscribeCategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * 同步所有有效账号的订阅消息类目
     *
     * @return array{syncCount: int, errorCount: int, errors: array<int, array{account: string, error: string}>}
     */
    public function syncAllAccounts(): array
    {
        $syncCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($this->accountRepository->findBy(['valid' => true]) as $account) {
            try {
                $count = $this->syncCategoriesForAccount($account);
                $syncCount += $count;
            } catch (\Throwable $exception) {
                ++$errorCount;
                $errors[] = [
                    'account' => $account->getAppId(),
                    'error' => $exception->getMessage(),
                ];
                $this->logger->error('同步订阅消息类目失败', [
                    'account' => $account->getAppId(),
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return [
            'syncCount' => $syncCount,
            'errorCount' => $errorCount,
            'errors' => $errors,
        ];
    }

    public function syncCategoriesForAccount(Account $account): int
    {
        $request = new GetCategoryListRequest();
        $request->setAccount($account);

        $response = $this->client->request($request);

        // 验证响应数据类型
        if (!is_array($response)) {
            $this->logger->warning('微信API返回的响应数据格式不正确', [
                'account' => $account->getAppId(),
                'response_type' => gettype($response),
            ]);
            return 0;
        }

        return $this->processCategoryResponse($account, $response);
    }

    /**
     * @param array<mixed> $response
     */
    private function processCategoryResponse(Account $account, array $response): int
    {
        $count = 0;
        foreach ($response as $item) {
            if (is_array($item) && isset($item['id'])) {
                $this->createOrUpdateCategory($account, $item);
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function createOrUpdateCategory(Account $account, array $item): void
    {
        // 验证必需字段的存在性和类型
        if (!isset($item['id']) || !is_int($item['id'])) {
            $this->logger->warning('类目数据缺少有效的id字段', [
                'account' => $account->getAppId(),
                'item' => $item,
            ]);
            return;
        }

        if (!isset($item['name']) || !is_string($item['name'])) {
            $this->logger->warning('类目数据缺少有效的name字段', [
                'account' => $account->getAppId(),
                'item' => $item,
            ]);
            return;
        }

        $category = $this->categoryRepository->findOneBy([
            'account' => $account,
            'categoryId' => $item['id'],
        ]);

        if (null === $category) {
            $category = new SubscribeCategory();
            $category->setAccount($account);
            $category->setCategoryId($item['id']);
        }

        $category->setName($item['name']);
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }
}
