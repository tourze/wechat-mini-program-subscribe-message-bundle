<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Command\SyncSubscribeCategoriesCommand;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;

class SyncSubscribeCategoriesCommandTest extends TestCase
{
    public function testExecuteWithValidAccounts()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willReturn([
                ['id' => 1, 'name' => 'Category 1'],
                ['id' => 2, 'name' => 'Category 2']
            ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('error');

        $categoryRepository = $this->createMock(SubscribeCategoryRepository::class);
        $categoryRepository->expects($this->exactly(2))
            ->method('findOneBy')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))->method('persist');
        $entityManager->expects($this->exactly(2))->method('flush');

        $command = new SyncSubscribeCategoriesCommand(
            $accountRepository,
            $client,
            $logger,
            $categoryRepository,
            $entityManager
        );

        $application = new Application();
        $application->add($command);

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithExistingCategory()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $existingCategory = $this->createMock(SubscribeCategory::class);
        $existingCategory->expects($this->once())
            ->method('setName')
            ->with('Updated Category');
        
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willReturn([
                ['id' => 1, 'name' => 'Updated Category']
            ]);

        $logger = $this->createMock(LoggerInterface::class);

        $categoryRepository = $this->createMock(SubscribeCategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingCategory);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($existingCategory);
        $entityManager->expects($this->once())->method('flush');

        $command = new SyncSubscribeCategoriesCommand(
            $accountRepository,
            $client,
            $logger,
            $categoryRepository,
            $entityManager
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithClientException()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $exception = new \Exception('Client error');
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with('获取订阅消息目录时发生错误', [
                'account' => $account,
                'exception' => $exception,
            ]);

        $categoryRepository = $this->createMock(SubscribeCategoryRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new SyncSubscribeCategoriesCommand(
            $accountRepository,
            $client,
            $logger,
            $categoryRepository,
            $entityManager
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithInvalidResponseItem()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willReturn([
                'invalid_item',
                ['name' => 'No ID'],
                ['id' => 1, 'name' => 'Valid Category']
            ]);

        $logger = $this->createMock(LoggerInterface::class);

        $categoryRepository = $this->createMock(SubscribeCategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $command = new SyncSubscribeCategoriesCommand(
            $accountRepository,
            $client,
            $logger,
            $categoryRepository,
            $entityManager
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }
}