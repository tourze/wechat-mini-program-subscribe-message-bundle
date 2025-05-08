<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;

/**
 * @method SubscribeParam|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeParam|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeParam[]    findAll()
 * @method SubscribeParam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeParamRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeParam::class);
    }
}
