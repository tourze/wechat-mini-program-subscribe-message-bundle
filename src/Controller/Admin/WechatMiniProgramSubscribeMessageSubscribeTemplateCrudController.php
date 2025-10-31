<?php

namespace WechatMiniProgramSubscribeMessageBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminAction;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeTemplateSyncService;

/**
 * @extends AbstractCrudController<SubscribeTemplate>
 */
#[AdminCrud(routePath: '/wechat-mini-program-subscribe-message/subscribe-template', routeName: 'wechat_mini_program_subscribe_message_subscribe_template')]
final class WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly SubscribeTemplateSyncService $syncService,
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return SubscribeTemplate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('订阅模板')
            ->setEntityLabelInPlural('订阅模板')
            ->setPageTitle('index', '订阅模板')
            ->setPageTitle('detail', '订阅模板详情')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['priTmplId', 'title', 'content'])
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

        yield TextField::new('priTmplId', '模板ID')
            ->setMaxLength(50)
        ;

        yield TextField::new('title', '模板标题')
            ->setMaxLength(50)
        ;

        yield ChoiceField::new('type', '模板类型')
            ->setChoices([
                '一次性订阅消息' => SubscribeTemplateType::ONCE,
                '长期订阅消息' => SubscribeTemplateType::LONG,
            ])
            ->renderExpanded(false)
            ->renderAsBadges()
        ;

        yield BooleanField::new('valid', '是否有效')
            ->renderAsSwitch()
        ;

        yield TextareaField::new('content', '模板内容')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield TextareaField::new('example', '模板内容示例')
            ->hideOnIndex()
            ->setFormTypeOptions(['required' => false])
        ;

        yield CollectionField::new('params', '参数列表')
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
            ->add(TextFilter::new('priTmplId', '模板ID'))
            ->add(TextFilter::new('title', '模板标题'))
            ->add(TextFilter::new('content', '模板内容'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $syncAction = Action::new('sync', '同步模板', 'fa fa-sync')
            ->linkToCrudAction('sync')
            ->setCssClass('btn btn-success')
            ->createAsGlobalAction()
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $syncAction)
        ;
    }

    #[AdminAction(routeName: 'sync', routePath: '/sync')]
    public function sync(AdminContext $context): Response
    {
        $result = $this->syncService->syncAllAccounts();

        $syncCount = $result['syncCount'];
        $errorCount = $result['errorCount'];

        if (0 === $errorCount) {
            $this->addFlash('success', "同步成功！共同步 {$syncCount} 个模板");
        } else {
            $this->addFlash('warning', "部分同步成功！共同步 {$syncCount} 个模板，{$errorCount} 个账号同步失败");
        }

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl()
        ;

        return new RedirectResponse($url);
    }
}
