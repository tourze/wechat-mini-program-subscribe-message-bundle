<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

/**
 * @extends ServiceEntityRepository<SubscribeTemplate>
 */
#[AsRepository(entityClass: SubscribeTemplate::class)]
class SubscribeTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeTemplate::class);
    }

    public function save(SubscribeTemplate $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubscribeTemplate $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
