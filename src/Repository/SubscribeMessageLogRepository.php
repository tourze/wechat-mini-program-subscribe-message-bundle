<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

/**
 * @extends ServiceEntityRepository<SubscribeMessageLog>
 */
#[AsRepository(entityClass: SubscribeMessageLog::class)]
class SubscribeMessageLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeMessageLog::class);
    }

    public function save(SubscribeMessageLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SubscribeMessageLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
