<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

/**
 * @internal
 */
#[CoversClass(SubscribeTemplateRepository::class)]
#[RunTestsInSeparateProcesses]
final class SubscribeTemplateRepositoryTest extends AbstractRepositoryTestCase
{
    private SubscribeTemplateRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SubscribeTemplateRepository::class);
    }

    public function testFindOneByShouldReturnEntityWhenCriteriaMatches(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('unique-template-456');
        $entity->setTitle('Unique Template');
        $entity->setType(SubscribeTemplateType::LONG);
        $entity->setValid(true);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['priTmplId' => 'unique-template-456']);

        self::assertNotNull($result);
        self::assertEquals('unique-template-456', $result->getPriTmplId());
        self::assertEquals('Unique Template', $result->getTitle());
        self::assertEquals(SubscribeTemplateType::LONG, $result->getType());
    }

    public function testFindByShouldReturnEntitiesMatchingCriteria(): void
    {
        $entity1 = new SubscribeTemplate();
        $entity1->setPriTmplId('template-333');
        $entity1->setTitle('One Time Template 1');
        $entity1->setType(SubscribeTemplateType::ONCE);
        $entity1->setValid(true);
        $entity2 = new SubscribeTemplate();
        $entity2->setPriTmplId('template-444');
        $entity2->setTitle('One Time Template 2');
        $entity2->setType(SubscribeTemplateType::ONCE);
        $entity2->setValid(true);
        $entity3 = new SubscribeTemplate();
        $entity3->setPriTmplId('template-555');
        $entity3->setTitle('Long Term Template');
        $entity3->setType(SubscribeTemplateType::LONG);
        $entity3->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);
        $this->persistAndFlush($entity3);

        $results = $this->repository->findBy(['type' => SubscribeTemplateType::ONCE]);

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(SubscribeTemplate::class, $results);

        $foundEntity1 = false;
        $foundEntity2 = false;
        foreach ($results as $result) {
            if ('template-333' === $result->getPriTmplId()) {
                $foundEntity1 = true;
            }
            if ('template-444' === $result->getPriTmplId()) {
                $foundEntity2 = true;
            }
        }
        self::assertTrue($foundEntity1, 'Entity with priTmplId template-333 should be found');
        self::assertTrue($foundEntity2, 'Entity with priTmplId template-444 should be found');
    }

    public function testFindByValidShouldReturnOnlyValidEntities(): void
    {
        $entity1 = new SubscribeTemplate();
        $entity1->setPriTmplId('template-666');
        $entity1->setTitle('Valid Template');
        $entity1->setType(SubscribeTemplateType::ONCE);
        $entity1->setValid(true);
        $entity2 = new SubscribeTemplate();
        $entity2->setPriTmplId('template-777');
        $entity2->setTitle('Invalid Template');
        $entity2->setType(SubscribeTemplateType::ONCE);
        $entity2->setValid(false);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $results = $this->repository->findBy(['valid' => true]);

        self::assertNotEmpty($results);
        self::assertContainsOnlyInstancesOf(SubscribeTemplate::class, $results);

        $foundOurEntity = false;
        foreach ($results as $result) {
            self::assertTrue($result->isValid(), 'All returned entities should be valid');
            if ('template-666' === $result->getPriTmplId()) {
                $foundOurEntity = true;
                self::assertEquals('Valid Template', $result->getTitle());
            }
        }
        self::assertTrue($foundOurEntity, 'Our valid entity should be in the results');
    }

    public function testSaveAndFlushShouldPersistEntity(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('save-template-001');
        $entity->setTitle('Save Test Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);

        $this->repository->save($entity);

        $persistedEntity = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeTemplate::class, $persistedEntity);
        self::assertEquals('save-template-001', $persistedEntity->getPriTmplId());
        self::assertEquals('Save Test Template', $persistedEntity->getTitle());
    }

    public function testSaveWithoutFlushShouldNotPersistImmediately(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('save-no-flush-001');
        $entity->setTitle('Save No Flush Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);

        $this->repository->save($entity, false);
        self::getEntityManager()->clear();

        $result = $this->repository->find($entity->getId());
        self::assertNull($result);
    }

    public function testRemoveAndFlushShouldDeleteEntity(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('remove-template-001');
        $entity->setTitle('Remove Test Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        self::assertNull($result);
    }

    public function testRemoveWithoutFlushShouldNotDeleteImmediately(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('remove-no-flush-001');
        $entity->setTitle('Remove No Flush Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->clear();

        $result = $this->repository->find($id);
        self::assertInstanceOf(SubscribeTemplate::class, $result);
    }

    public function testFindByAccountIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('null-account-001');
        $entity->setTitle('Null Account Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-account-001' === $result->getPriTmplId()) {
                $found = true;
                self::assertNull($result->getAccount());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByContentIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('null-content-001');
        $entity->setTitle('Null Content Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setContent(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['content' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-content-001' === $result->getPriTmplId()) {
                $found = true;
                self::assertNull($result->getContent());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByExampleIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('null-example-001');
        $entity->setTitle('Null Example Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setExample(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['example' => null]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-example-001' === $result->getPriTmplId()) {
                $found = true;
                self::assertNull($result->getExample());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithAccountIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('count-null-account-001');
        $entity->setTitle('Count Null Account Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithContentIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('count-null-content-001');
        $entity->setTitle('Count Null Content Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setContent(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['content' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithExampleIsNull(): void
    {
        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('count-null-example-001');
        $entity->setTitle('Count Null Example Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setExample(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['example' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindByWithAccount(): void
    {
        $account = new Account();
        $account->setName('Test Account Template 001');
        $account->setAppId('test-app-id-template-001');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('template-with-account-001');
        $entity->setTitle('Template With Account');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['account' => $account]);

        self::assertIsArray($results);
        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('template-with-account-001' === $result->getPriTmplId()) {
                $found = true;
                self::assertNotNull($result->getAccount());
                self::assertEquals($account->getId(), $result->getAccount()->getId());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithAccount(): void
    {
        $account = new Account();
        $account->setName('Test Account Template 002');
        $account->setAppId('test-app-id-template-002');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('template-count-account-001');
        $entity->setTitle('Template Count Account');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['account' => $account]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $account = new Account();
        $account->setName('Test Account FindOne Association');
        $account->setAppId('test-app-id-findone-association');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        $entity = new SubscribeTemplate();
        $entity->setPriTmplId('template-findone-association-001');
        $entity->setTitle('FindOne Association Template');
        $entity->setType(SubscribeTemplateType::ONCE);
        $entity->setValid(true);
        $entity->setAccount($account);
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['account' => $account]);

        self::assertInstanceOf(SubscribeTemplate::class, $result);
        self::assertEquals($account->getId(), $result->getAccount()?->getId());
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $account = new Account();
        $account->setName('Test Account Count Association');
        $account->setAppId('test-app-id-count-association');
        $account->setAppSecret('test-secret');
        $this->persistAndFlush($account);

        for ($i = 1; $i <= 3; ++$i) {
            $entity = new SubscribeTemplate();
            $entity->setPriTmplId('template-count-association-' . $i);
            $entity->setTitle('Count Association Template ' . $i);
            $entity->setType(SubscribeTemplateType::ONCE);
            $entity->setValid(true);
            $entity->setAccount($account);
            $this->persistAndFlush($entity);
        }

        $count = $this->repository->count(['account' => $account]);

        self::assertEquals(3, $count);
    }

    /**
     * @return ServiceEntityRepository<SubscribeTemplate>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    protected function createNewEntity(): object
    {
        $entity = new SubscribeTemplate();

        // 设置必需的字段
        $entity->setPriTmplId('test-tmpl-' . uniqid());
        $entity->setTitle('Test Template ' . uniqid());
        $entity->setType(SubscribeTemplateType::ONCE);

        return $entity;
    }
}
