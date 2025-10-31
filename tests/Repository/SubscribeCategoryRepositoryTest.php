<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeCategoryRepository;

/**
 * @internal
 */
#[CoversClass(SubscribeCategoryRepository::class)]
#[RunTestsInSeparateProcesses]
final class SubscribeCategoryRepositoryTest extends AbstractRepositoryTestCase
{
    private SubscribeCategoryRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SubscribeCategoryRepository::class);
    }

    public function testFindOneByShouldReturnEntityWhenCriteriaMatches(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(456);
        $entity->setName('Unique Category');
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['categoryId' => 456]);

        self::assertInstanceOf(SubscribeCategory::class, $result);
        self::assertEquals(456, $result->getCategoryId());
        self::assertEquals('Unique Category', $result->getName());
    }

    public function testFindByShouldReturnEntitiesMatchingCriteria(): void
    {
        $entity1 = new SubscribeCategory();
        $entity1->setCategoryId(333);
        $entity1->setName('Test Name');
        $entity2 = new SubscribeCategory();
        $entity2->setCategoryId(444);
        $entity2->setName('Other Name');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $results = $this->repository->findBy(['name' => 'Test Name']);

        self::assertCount(1, $results);
        self::assertContainsOnlyInstancesOf(SubscribeCategory::class, $results);
        self::assertEquals('Test Name', $results[0]->getName());
    }

    public function testSaveShouldPersistEntityToDatabase(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(1111);
        $entity->setName('Save Test Category');

        $this->repository->save($entity);

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeCategory::class, $saved);
        self::assertEquals(1111, $saved->getCategoryId());
        self::assertEquals('Save Test Category', $saved->getName());
    }

    public function testSaveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(1112);
        $entity->setName('No Flush Test Category');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeCategory::class, $saved);
        self::assertEquals(1112, $saved->getCategoryId());
    }

    public function testRemoveShouldDeleteEntityFromDatabase(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(1113);
        $entity->setName('Remove Test Category');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testRemoveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(1114);
        $entity->setName('No Flush Remove Test Category');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->flush();

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testFindOneByShouldRespectOrderByParameter(): void
    {
        $entity1 = new SubscribeCategory();
        $entity1->setCategoryId(5001);
        $entity1->setName('Same Name');
        $entity2 = new SubscribeCategory();
        $entity2->setCategoryId(5002);
        $entity2->setName('Same Name');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findOneBy(['name' => 'Same Name'], ['categoryId' => 'ASC']);

        self::assertInstanceOf(SubscribeCategory::class, $result);
        self::assertEquals(5001, $result->getCategoryId());
    }

    public function testFindByWithAccountAssociationShouldWork(): void
    {
        $account = new Account();
        $account->setName('Test Account Association 001');
        $account->setAppId('test-app-id-association-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeCategory();
        $entity->setCategoryId(6001);
        $entity->setName('Association Test');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertCount(1, $results);
        self::assertContainsOnlyInstancesOf(SubscribeCategory::class, $results);
        self::assertNotNull($results[0]->getAccount());
        self::assertSame($account, $results[0]->getAccount());
    }

    public function testCountWithAccountAssociationShouldWork(): void
    {
        $account = new Account();
        $account->setName('Test Account Count Association 001');
        $account->setAppId('test-app-id-count-association-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity1 = new SubscribeCategory();
        $entity1->setCategoryId(6002);
        $entity1->setName('Association Count Test 1');
        $entity1->setAccount($account);
        $entity2 = new SubscribeCategory();
        $entity2->setCategoryId(6003);
        $entity2->setName('Association Count Test 2');
        $entity2->setAccount($account);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(2, $count);
    }

    public function testFindByWithNullAccountShouldWork(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(7001);
        $entity->setName('Null Account Test');
        $entity->setAccount(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if (7001 === $result->getCategoryId()) {
                $found = true;
                self::assertNull($result->getAccount());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithNullAccountShouldWork(): void
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId(7002);
        $entity->setName('Null Account Count Test');
        $entity->setAccount(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account = new Account();
        $account->setName('Test Account FindOne Association 001');
        $account->setAppId('test-app-id-findone-association-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeCategory();
        $entity->setCategoryId(8001);
        $entity->setName('Association FindOne Test');
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        self::assertInstanceOf(SubscribeCategory::class, $result);
        self::assertNotNull($result->getAccount());
        self::assertSame($account, $result->getAccount());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account = new Account();
        $account->setName('Test Account Count Association 002');
        $account->setAppId('test-app-id-count-association-002');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        for ($i = 1; $i <= 3; ++$i) {
            $entity = new SubscribeCategory();
            $entity->setCategoryId(8003 + $i);
            $entity->setName('Count Association Test ' . $i);
            $entity->setAccount($account);
            $this->persistAndFlush($entity);
        }

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(3, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new SubscribeCategory();
        $entity->setCategoryId((int) uniqid());
        $entity->setName('Test Category ' . uniqid());

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<SubscribeCategory>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
