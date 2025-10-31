<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

/**
 * @extends ServiceEntityRepository<SubscribeCategory>
 */
#[AsRepository(entityClass: SubscribeCategory::class)]
class SubscribeCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeCategory::class);
    }

    public function save(SubscribeCategory $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubscribeCategory $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
