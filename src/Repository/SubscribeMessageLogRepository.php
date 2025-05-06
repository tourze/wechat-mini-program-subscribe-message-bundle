<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

/**
 * @method SubscribeMessageLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeMessageLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeMessageLog[]    findAll()
 * @method SubscribeMessageLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeMessageLogRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeMessageLog::class);
    }
}
