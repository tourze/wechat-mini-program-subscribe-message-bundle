<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

/**
 * @method SubscribeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeCategory[]    findAll()
 * @method SubscribeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeCategoryRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeCategory::class);
    }
}
