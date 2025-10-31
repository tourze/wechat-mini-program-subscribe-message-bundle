<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

/**
 * @extends AbstractCrudController<SubscribeParam>
 */
#[AdminCrud(routePath: '/wechat-mini-program-subscribe-message/subscribe-param', routeName: 'wechat_mini_program_subscribe_message_subscribe_param')]
final class WechatMiniProgramSubscribeMessageSubscribeParamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubscribeParam::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('订阅参数')
            ->setEntityLabelInPlural('订阅参数')
            ->setPageTitle('index', '订阅参数')
            ->setPageTitle('detail', '订阅参数详情')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['code', 'mapExpression'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield AssociationField::new('template', '订阅模板')
            ->hideOnIndex()
        ;

        yield ChoiceField::new('type', '参数类型')
            ->setChoices([
                '事物' => SubscribeTemplateData::THING,
                '数字' => SubscribeTemplateData::NUMBER,
                '字母' => SubscribeTemplateData::LETTER,
                '符号' => SubscribeTemplateData::SYMBOL,
                '字符串' => SubscribeTemplateData::CHARACTER_STRING,
                '时间' => SubscribeTemplateData::TIME,
                '日期' => SubscribeTemplateData::DATE,
                '金额' => SubscribeTemplateData::AMOUNT,
                '姓名' => SubscribeTemplateData::NAME,
                '汉字' => SubscribeTemplateData::PHRASE,
                '枚举值' => SubscribeTemplateData::ENUM,
            ])
            ->renderExpanded(false)
            ->renderAsBadges()
        ;

        yield TextField::new('code', '参数代码')
            ->setMaxLength(30)
        ;

        yield TextareaField::new('mapExpression', '数据映射表达式')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield ArrayField::new('enumValues', '枚举数据')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('code', '参数代码'))
            ->add(TextFilter::new('mapExpression', '数据映射表达式'))
        ;
    }
}
