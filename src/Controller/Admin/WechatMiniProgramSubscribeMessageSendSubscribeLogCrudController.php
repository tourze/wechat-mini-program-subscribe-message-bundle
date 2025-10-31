<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

/**
 * @extends AbstractCrudController<SendSubscribeLog>
 */
#[AdminCrud(routePath: '/wechat-mini-program-subscribe-message/send-subscribe-log', routeName: 'wechat_mini_program_subscribe_message_send_subscribe_log')]
final class WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SendSubscribeLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('发送订阅消息日志')
            ->setEntityLabelInPlural('发送订阅消息日志')
            ->setPageTitle('index', '发送订阅消息日志')
            ->setPageTitle('detail', '发送订阅消息日志详情')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['templateId', 'remark', 'createdFromIp'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield AssociationField::new('account', '微信小程序账号')
            ->hideOnIndex()
        ;

        yield AssociationField::new('user', '用户')
            ->hideOnIndex()
        ;

        yield TextField::new('templateId', '模板ID')
            ->setMaxLength(50)
        ;

        yield AssociationField::new('subscribeTemplate', '订阅模板')
            ->hideOnIndex()
        ;

        yield TextareaField::new('result', '发送结果')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield TextField::new('remark', '备注')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield TextField::new('createdFromIp', '来源IP')
            ->hideOnForm()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('templateId', '模板ID'))
            ->add(TextFilter::new('createdFromIp', '来源IP'))
            ->add(TextFilter::new('remark', '备注'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
