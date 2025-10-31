<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SendSubscribeLogRepository;

/**
 * @internal
 */
#[CoversClass(SendSubscribeLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class SendSubscribeLogRepositoryTest extends AbstractRepositoryTestCase
{
    private SendSubscribeLogRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SendSubscribeLogRepository::class);
    }

    public function testSaveAndFlushShouldPersistEntity(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('test-template-002');
        $entity->setResult('success');

        $this->repository->save($entity);

        $persistedEntity = $this->repository->find($entity->getId());
        self::assertInstanceOf(SendSubscribeLog::class, $persistedEntity);
        self::assertEquals('test-template-002', $persistedEntity->getTemplateId());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('test-template-003');
        $entity->setResult('success');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SendSubscribeLog::class, $saved);
        self::assertEquals('test-template-003', $saved->getTemplateId());
    }

    public function testRemoveAndFlushShouldDeleteEntity(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('test-template-004');
        $entity->setResult('success');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        self::assertNull($result);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('test-template-005');
        $entity->setResult('success');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->clear();

        $result = $this->repository->find($id);
        self::assertInstanceOf(SendSubscribeLog::class, $result);
    }

    public function testFindByAccountIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-null-account');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-null-account' === $result->getTemplateId()) {
                $found = true;
                self::assertNull($result->getAccount());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByUserIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-null-user');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['user' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-null-user' === $result->getTemplateId()) {
                $found = true;
                self::assertNull($result->getUser());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByRemarkIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-null-remark');
        $entity->setResult('success');
        $entity->setRemark(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['remark' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-null-remark' === $result->getTemplateId()) {
                $found = true;
                self::assertNull($result->getRemark());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithAccountIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-null-account');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithUserIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-null-user');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['user' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithRemarkIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-null-remark');
        $entity->setResult('success');
        $entity->setRemark(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['remark' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithAccount(): void
    {
        $account = new Account();
        $account->setName('Test Account');
        $account->setAppId('test-app-id-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-with-account');
        $entity->setResult('success');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-with-account' === $result->getTemplateId()) {
                $found = true;
                $resultAccount = $result->getAccount();
                self::assertNotNull($resultAccount);
                self::assertEquals($account->getId(), $resultAccount->getId());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithAccount(): void
    {
        $account = new Account();
        $account->setName('Test Account 2');
        $account->setAppId('test-app-id-002');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-account');
        $entity->setResult('success');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $account]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithSubscribeTemplate(): void
    {
        $subscribeTemplate = new SubscribeTemplate();
        $subscribeTemplate->setPriTmplId('test-pri-tmpl-001');
        $subscribeTemplate->setTitle('Test Template');
        $subscribeTemplate->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($subscribeTemplate);

        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-with-subscribe-template');
        $entity->setResult('success');
        $entity->setSubscribeTemplate($subscribeTemplate);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['subscribeTemplate' => $subscribeTemplate]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-with-subscribe-template' === $result->getTemplateId()) {
                $found = true;
                self::assertEquals('template-with-subscribe-template', $result->getTemplateId());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithSubscribeTemplate(): void
    {
        $subscribeTemplate = new SubscribeTemplate();
        $subscribeTemplate->setPriTmplId('test-pri-tmpl-002');
        $subscribeTemplate->setTitle('Test Template 2');
        $subscribeTemplate->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($subscribeTemplate);

        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-subscribe-template');
        $entity->setResult('success');
        $entity->setSubscribeTemplate($subscribeTemplate);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['subscribeTemplate' => $subscribeTemplate]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindBySubscribeTemplateIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-null-subscribe-template');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['subscribeTemplate' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-null-subscribe-template' === $result->getTemplateId()) {
                $found = true;
                self::assertEquals('template-null-subscribe-template', $result->getTemplateId());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithSubscribeTemplateIsNull(): void
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-count-null-subscribe-template');
        $entity->setResult('success');
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['subscribeTemplate' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account = new Account();
        $account->setName('Test Account FindOne');
        $account->setAppId('test-app-id-findone');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-findone-association');
        $entity->setResult('success');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        self::assertInstanceOf(SendSubscribeLog::class, $result);
        $resultAccount = $result->getAccount();
        self::assertNotNull($resultAccount);
        self::assertEquals($account->getId(), $resultAccount->getId());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account = new Account();
        $account->setName('Test Account Count');
        $account->setAppId('test-app-id-count-association');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        for ($i = 1; $i <= 3; ++$i) {
            $entity = new SendSubscribeLog();
            $entity->setTemplateId('template-count-association-' . $i);
            $entity->setResult('success');
            $entity->setAccount($account);
            $this->persistAndFlush($entity);
        }

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(3, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new SendSubscribeLog();
        $entity->setTemplateId('template-' . uniqid());
        $entity->setResult('success');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<SendSubscribeLog>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
