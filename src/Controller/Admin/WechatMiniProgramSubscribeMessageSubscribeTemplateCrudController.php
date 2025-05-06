<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

class WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubscribeTemplate::class;
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
