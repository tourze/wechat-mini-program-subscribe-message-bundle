<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

/**
 * @method SendSubscribeLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendSubscribeLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendSubscribeLog[]    findAll()
 * @method SendSubscribeLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendSubscribeLogRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SendSubscribeLog::class);
    }
}
