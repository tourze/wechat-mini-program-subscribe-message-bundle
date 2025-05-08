<?php

namespace WechatMiniProgramSubscribeMessageBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

/**
 * @method SubscribeTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscribeTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscribeTemplate[]    findAll()
 * @method SubscribeTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribeTemplateRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscribeTemplate::class);
    }
}
