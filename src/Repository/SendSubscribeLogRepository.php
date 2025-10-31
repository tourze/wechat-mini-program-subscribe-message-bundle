<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

/**
 * @extends ServiceEntityRepository<SendSubscribeLog>
 */
#[AsRepository(entityClass: SendSubscribeLog::class)]
class SendSubscribeLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SendSubscribeLog::class);
    }

    public function save(SendSubscribeLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SendSubscribeLog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
