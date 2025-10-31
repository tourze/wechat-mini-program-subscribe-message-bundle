<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;

/**
 * @extends ServiceEntityRepository<SubscribeParam>
 */
#[AsRepository(entityClass: SubscribeParam::class)]
class SubscribeParamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeParam::class);
    }

    public function save(SubscribeParam $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubscribeParam $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
