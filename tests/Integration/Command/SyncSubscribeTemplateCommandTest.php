<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramSubscribeMessageBundle\Command\SyncSubscribeTemplateCommand;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

class SyncSubscribeTemplateCommandTest extends TestCase
{
    public function testExecuteWithValidResponse()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $account->method('getAppId')->willReturn('test-app-id');
        
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willReturn([
                'data' => [
                    [
                        'priTmplId' => 'template-123',
                        'type' => 2,
                        'title' => 'Test Template',
                        'content' => 'Hello {{thing1.DATA}}',
                        'example' => 'Hello World',
                        'keywordEnumValueList' => []
                    ]
                ]
            ]);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('error');

        $subscribeTemplateRepository = $this->createMock(SubscribeTemplateRepository::class);
        $subscribeTemplateRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $subscribeParamRepository = $this->createMock(SubscribeParamRepository::class);
        $subscribeParamRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))->method('persist'); // template + param
        $entityManager->expects($this->exactly(2))->method('flush');
        $entityManager->expects($this->once())->method('detach');

        $command = new SyncSubscribeTemplateCommand(
            $accountRepository,
            $entityManager,
            $client,
            $subscribeTemplateRepository,
            $subscribeParamRepository,
            $logger
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithExistingTemplate()
    {
        // Arrange
        $account = $this->createMock(Account::class);
        $account->method('getAppId')->willReturn('test-app-id');
        
        $existingTemplate = $this->createMock(SubscribeTemplate::class);
        $existingTemplate->expects($this->once())->method('setType');
        $existingTemplate->expects($this->once())->method('setTitle');
        $existingTemplate->expects($this->once())->method('setContent');
        $existingTemplate->expects($this->once())->method('setExample');
        $existingTemplate->expects($this->once())->method('setValid');
        $existingTemplate->method('getContent')->willReturn('Hello {{thing1.DATA}}');

        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$account]);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('request')
            ->willReturn([
                'data' => [
                    [
                        'priTmplId' => 'template-123',
                        'type' => 2,
                        'title' => 'Updated Template',
                        'content' => 'Hello {{thing1.DATA}}',
                        'example' => 'Hello Updated',
                        'keywordEnumValueList' => []
                    ]
                ]
            ]);

        $logger = $this->createMock(LoggerInterface::class);

        $subscribeTemplateRepository = $this->createMock(SubscribeTemplateRepository::class);
        $subscribeTemplateRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($existingTemplate);

        $subscribeParamRepository = $this->createMock(SubscribeParamRepository::class);
        $subscribeParamRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->exactly(2))->method('persist');
        $entityManager->expects($this->exactly(2))->method('flush');
        $entityManager->expects($this->once())->method('detach');

        $command = new SyncSubscribeTemplateCommand(
            $accountRepository,
            $entityManager,
            $client,
            $subscribeTemplateRepository,
            $subscribeParamRepository,
            $logger
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
        $account->method('getAppId')->willReturn('test-app-id');
        
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
            ->with('拉取小程序模板ID失败:[test-app-id]', [
                'exception' => $exception,
                'account' => $account,
            ]);

        $subscribeTemplateRepository = $this->createMock(SubscribeTemplateRepository::class);
        $subscribeParamRepository = $this->createMock(SubscribeParamRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new SyncSubscribeTemplateCommand(
            $accountRepository,
            $entityManager,
            $client,
            $subscribeTemplateRepository,
            $subscribeParamRepository,
            $logger
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }

    public function testExecuteWithEmptyResponseData()
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
            ->willReturn(['message' => 'success']);

        $logger = $this->createMock(LoggerInterface::class);
        $subscribeTemplateRepository = $this->createMock(SubscribeTemplateRepository::class);
        $subscribeParamRepository = $this->createMock(SubscribeParamRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $command = new SyncSubscribeTemplateCommand(
            $accountRepository,
            $entityManager,
            $client,
            $subscribeTemplateRepository,
            $subscribeParamRepository,
            $logger
        );

        $tester = new CommandTester($command);

        // Act
        $result = $tester->execute([]);

        // Assert
        $this->assertEquals(0, $result);
    }
}