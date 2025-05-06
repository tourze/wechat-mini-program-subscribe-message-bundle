<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

class WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubscribeCategory::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
