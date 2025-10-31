<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

/**
 * @extends AbstractCrudController<SubscribeMessageLog>
 */
#[AdminCrud(routePath: '/wechat-mini-program-subscribe-message/subscribe-message-log', routeName: 'wechat_mini_program_subscribe_message_subscribe_message_log')]
final class WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubscribeMessageLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('订阅消息日志')
            ->setEntityLabelInPlural('订阅消息日志')
            ->setPageTitle('index', '订阅消息日志')
            ->setPageTitle('detail', '订阅消息日志详情')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['templateId', 'subscribeStatus', 'resultMsgId', 'createdFromIp'])
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

        yield TextField::new('subscribeStatus', '订阅状态')
            ->setMaxLength(20)
        ;

        yield TextField::new('resultMsgId', '发送结果MsgId')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield IntegerField::new('resultCode', '发送结果错误码')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield TextField::new('resultStatus', '发送结果状态')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield TextareaField::new('rawData', '原始数据')
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
            ->add(TextFilter::new('subscribeStatus', '订阅状态'))
            ->add(TextFilter::new('resultMsgId', '发送结果MsgId'))
            ->add(TextFilter::new('createdFromIp', '来源IP'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
