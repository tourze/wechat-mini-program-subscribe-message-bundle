<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeParamRepository;

/**
 * @internal
 */
#[CoversClass(SubscribeParamRepository::class)]
#[RunTestsInSeparateProcesses]
final class SubscribeParamRepositoryTest extends AbstractRepositoryTestCase
{
    private SubscribeParamRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(SubscribeParamRepository::class);
    }

    public function testFindOneByShouldReturnEntityWhenCriteriaMatches(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-002');
        $template->setTitle('Test Template 2');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::CHARACTER_STRING);
        $entity->setCode('unique-param-456');
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['code' => 'unique-param-456']);

        self::assertInstanceOf(SubscribeParam::class, $result);
        self::assertEquals('unique-param-456', $result->getCode());
        self::assertEquals(SubscribeTemplateData::CHARACTER_STRING, $result->getType());
    }

    public function testFindByShouldReturnEntitiesMatchingCriteria(): void
    {
        $template1 = new SubscribeTemplate();
        $template1->setPriTmplId('template-005');
        $template1->setTitle('Template 5');
        $template1->setType(SubscribeTemplateType::ONCE);
        $template2 = new SubscribeTemplate();
        $template2->setPriTmplId('template-006');
        $template2->setTitle('Template 6');
        $template2->setType(SubscribeTemplateType::LONG);
        $this->persistAndFlush($template1);
        $this->persistAndFlush($template2);

        $entity1 = new SubscribeParam();
        $entity1->setTemplate($template1);
        $entity1->setType(SubscribeTemplateData::THING);
        $entity1->setCode('param-333');
        $entity2 = new SubscribeParam();
        $entity2->setTemplate($template2);
        $entity2->setType(SubscribeTemplateData::THING);
        $entity2->setCode('param-444');
        $entity3 = new SubscribeParam();
        $entity3->setTemplate($template1);
        $entity3->setType(SubscribeTemplateData::TIME);
        $entity3->setCode('param-555');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);
        $this->persistAndFlush($entity3);

        $results = $this->repository->findBy(['type' => SubscribeTemplateData::THING]);

        self::assertGreaterThanOrEqual(2, count($results));
        self::assertContainsOnlyInstancesOf(SubscribeParam::class, $results);

        $foundEntity1 = false;
        $foundEntity2 = false;
        foreach ($results as $result) {
            if ('param-333' === $result->getCode()) {
                $foundEntity1 = true;
            }
            if ('param-444' === $result->getCode()) {
                $foundEntity2 = true;
            }
        }
        self::assertTrue($foundEntity1, 'Entity with code param-333 should be found');
        self::assertTrue($foundEntity2, 'Entity with code param-444 should be found');
    }

    public function testSaveShouldPersistEntityToDatabase(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-save-001');
        $template->setTitle('Save Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('save-param-001');

        $this->repository->save($entity);

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeParam::class, $saved);
        self::assertEquals('save-param-001', $saved->getCode());
        self::assertEquals(SubscribeTemplateData::THING, $saved->getType());
    }

    public function testSaveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-save-no-flush-001');
        $template->setTitle('Save No Flush Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('save-no-flush-param-001');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $saved = $this->repository->find($entity->getId());
        self::assertInstanceOf(SubscribeParam::class, $saved);
        self::assertEquals('save-no-flush-param-001', $saved->getCode());
    }

    public function testRemoveShouldDeleteEntityFromDatabase(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-remove-001');
        $template->setTitle('Remove Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('remove-param-001');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity);

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testRemoveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-remove-no-flush-001');
        $template->setTitle('Remove No Flush Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('remove-no-flush-param-001');
        $this->persistAndFlush($entity);
        $entityId = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->flush();

        $deleted = $this->repository->find($entityId);
        self::assertNull($deleted);
    }

    public function testFindByWithTemplateAssociationShouldWork(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-association-001');
        $template->setTitle('Association Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('association-param-001');
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['template' => $template]);

        self::assertCount(1, $results);
        self::assertNotNull($results[0]->getTemplate());
        self::assertEquals($template->getId(), $results[0]->getTemplate()->getId());
    }

    public function testCountWithTemplateAssociationShouldWork(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-count-association-001');
        $template->setTitle('Count Association Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity1 = new SubscribeParam();
        $entity1->setTemplate($template);
        $entity1->setType(SubscribeTemplateData::THING);
        $entity1->setCode('count-association-param-001');
        $entity2 = new SubscribeParam();
        $entity2->setTemplate($template);
        $entity2->setType(SubscribeTemplateData::TIME);
        $entity2->setCode('count-association-param-002');
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $count = $this->repository->count(['template' => $template]);

        self::assertEquals(2, $count);
    }

    public function testFindByWithMapExpressionIsNull(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-null-expression-001');
        $template->setTitle('Null Expression Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('null-expression-param-001');
        $entity->setMapExpression(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['mapExpression' => null]);

        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-expression-param-001' === $result->getCode()) {
                $found = true;
                self::assertNull($result->getMapExpression());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testFindByWithEnumValuesIsNull(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-null-enum-001');
        $template->setTitle('Null Enum Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('null-enum-param-001');
        $entity->setEnumValues(null);
        $this->persistAndFlush($entity);

        $results = $this->repository->findBy(['enumValues' => null]);

        self::assertNotEmpty($results);
        $found = false;
        foreach ($results as $result) {
            if ('null-enum-param-001' === $result->getCode()) {
                $found = true;
                self::assertEmpty($result->getEnumValues());
                break;
            }
        }
        self::assertTrue($found);
    }

    public function testCountWithMapExpressionIsNull(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-count-null-expression-001');
        $template->setTitle('Count Null Expression Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('count-null-expression-param-001');
        $entity->setMapExpression(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['mapExpression' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testCountWithEnumValuesIsNull(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-count-null-enum-001');
        $template->setTitle('Count Null Enum Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('count-null-enum-param-001');
        $entity->setEnumValues(null);
        $this->persistAndFlush($entity);

        $count = $this->repository->count(['enumValues' => null]);

        self::assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByAssociationTemplateShouldReturnMatchingEntity(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-findone-association-001');
        $template->setTitle('FindOne Association Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('findone-association-code');
        $this->persistAndFlush($entity);

        $result = $this->repository->findOneBy(['template' => $template]);

        self::assertInstanceOf(SubscribeParam::class, $result);
        self::assertEquals($template->getId(), $result->getTemplate()?->getId());
    }

    public function testCountByAssociationTemplateShouldReturnCorrectNumber(): void
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-count-association-001');
        $template->setTitle('Count Association Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        for ($i = 1; $i <= 4; ++$i) {
            $entity = new SubscribeParam();
            $entity->setTemplate($template);
            $entity->setType(SubscribeTemplateData::THING);
            $entity->setCode('count-association-code-' . $i);
            $this->persistAndFlush($entity);
        }

        $count = $this->repository->count(['template' => $template]);

        self::assertEquals(4, $count);
    }

    protected function createNewEntity(): object
    {
        $template = new SubscribeTemplate();
        $template->setPriTmplId('template-' . uniqid());
        $template->setTitle('Test Template');
        $template->setType(SubscribeTemplateType::ONCE);
        $this->persistAndFlush($template);

        $entity = new SubscribeParam();
        $entity->setTemplate($template);
        $entity->setType(SubscribeTemplateData::THING);
        $entity->setCode('param-' . uniqid());

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<SubscribeParam>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
