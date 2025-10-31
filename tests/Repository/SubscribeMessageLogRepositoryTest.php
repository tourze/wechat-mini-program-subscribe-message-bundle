<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeMessageLogRepository;

/**
 * @internal
 */
#[CoversClass(SubscribeMessageLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class SubscribeMessageLogRepositoryTest extends AbstractRepositoryTestCase
{
    private SubscribeMessageLogRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SubscribeMessageLogRepository::class);
    }

    public function testFindOneByShouldReturnEntityWhenCriteriaMatches(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('unique-template-456');
        $entity->setSubscribeStatus('reject');
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['templateId' => 'unique-template-456']);

        self::assertInstanceOf(SubscribeMessageLog::class, $result);
        self::assertEquals('unique-template-456', $result->getTemplateId());
        self::assertEquals('reject', $result->getSubscribeStatus());
    }

    public function testFindByShouldReturnEntitiesMatchingCriteria(): void
    {
        $uniqueStatus = 'accept-test-' . uniqid();
        $entity1 = new SubscribeMessageLog();
        $entity1->setTemplateId('template-333');
        $entity1->setSubscribeStatus($uniqueStatus);
        $entity2 = new SubscribeMessageLog();
        $entity2->setTemplateId('template-444');
        $entity2->setSubscribeStatus($uniqueStatus);
        $entity3 = new SubscribeMessageLog();
        $entity3->setTemplateId('template-555');
        $entity3->setSubscribeStatus('reject');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);
        $this->persistAndFlush($entity3);

        $results = $this->repository->findBy(['subscribeStatus' => $uniqueStatus]);

        self::assertCount(2, $results);
        self::assertContainsOnlyInstancesOf(SubscribeMessageLog::class, $results);
    }

    public function testFindOneByWithOrderBy(): void
    {
        $uniqueStatus = 'accept-order-' . uniqid();
        $entity1 = new SubscribeMessageLog();
        $entity1->setTemplateId('findone-order-002');
        $entity1->setSubscribeStatus($uniqueStatus);
        $entity2 = new SubscribeMessageLog();
        $entity2->setTemplateId('findone-order-001');
        $entity2->setSubscribeStatus($uniqueStatus);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['subscribeStatus' => $uniqueStatus], ['templateId' => 'ASC']);

        self::assertInstanceOf(SubscribeMessageLog::class, $result);
        self::assertEquals('findone-order-001', $result->getTemplateId());
    }

    public function testSaveShouldPersistEntityToDatabase(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('save-template-001');
        $entity->setSubscribeStatus('accept');

        $this->repository->save($entity);

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeMessageLog::class, $saved);
        self::assertEquals('save-template-001', $saved->getTemplateId());
        self::assertEquals('accept', $saved->getSubscribeStatus());
    }

    public function testSaveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('save-no-flush-001');
        $entity->setSubscribeStatus('accept');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeMessageLog::class, $saved);
        self::assertEquals('save-no-flush-001', $saved->getTemplateId());
    }

    public function testRemoveShouldDeleteEntityFromDatabase(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('remove-template-001');
        $entity->setSubscribeStatus('accept');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testRemoveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('remove-no-flush-001');
        $entity->setSubscribeStatus('accept');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->flush();

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testFindByWithAccountAssociationShouldWork(): void
    {
        $account = new Account();
        $account->setName('Test Account Log 001');
        $account->setAppId('test-app-id-log-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('association-template-001');
        $entity->setSubscribeStatus('accept');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(1, $results);
        self::assertNotNull($results[0]->getAccount());
        self::assertEquals($account->getAppId(), $results[0]->getAccount()->getAppId());
    }

    public function testCountWithAccountAssociationShouldWork(): void
    {
        $account = new Account();
        $account->setName('Test Account Log 002');
        $account->setAppId('test-app-id-log-002');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity1 = new SubscribeMessageLog();
        $entity1->setTemplateId('count-association-template-001');
        $entity1->setSubscribeStatus('accept');
        $entity1->setAccount($account);
        $entity2 = new SubscribeMessageLog();
        $entity2->setTemplateId('count-association-template-002');
        $entity2->setSubscribeStatus('reject');
        $entity2->setAccount($account);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByWithNullAccountShouldWork(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('null-account-template-001');
        $entity->setSubscribeStatus('accept');
        $entity->setAccount(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-account-template-001' === $result->getTemplateId()) {
                $found = true;
                self::assertNull($result->getAccount());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByWithNullRawDataShouldWork(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('null-raw-data-template-001');
        $entity->setSubscribeStatus('accept');
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['rawData' => null]);

        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-raw-data-template-001' === $result->getTemplateId()) {
                $found = true;
                self::assertNull($result->getRawData());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithNullAccountShouldWork(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('count-null-account-template-001');
        $entity->setSubscribeStatus('accept');
        $entity->setAccount(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithNullRawDataShouldWork(): void
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('count-null-raw-data-template-001');
        $entity->setSubscribeStatus('accept');
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['rawData' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account = new Account();
        $account->setName('Test Account FindOne Association');
        $account->setAppId('test-app-id-findone-association');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('findone-association-template');
        $entity->setSubscribeStatus('accept');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        self::assertInstanceOf(SubscribeMessageLog::class, $result);
        self::assertNotNull($result->getAccount());
        self::assertEquals($account->getAppId(), $result->getAccount()->getAppId());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account = new Account();
        $account->setName('Test Account Count Association');
        $account->setAppId('test-app-id-count-association');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        for ($i = 1; $i <= 3; ++$i) {
            $entity = new SubscribeMessageLog();
            $entity->setTemplateId('count-association-template-' . $i);
            $entity->setSubscribeStatus('accept');
            $entity->setAccount($account);
            $this->persistAndFlush($entity);
        }

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(3, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new SubscribeMessageLog();
        $entity->setTemplateId('template-' . uniqid());
        $entity->setSubscribeStatus('accept');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<SubscribeMessageLog>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
