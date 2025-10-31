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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;
use WechatMiniProgramSubscribeMessageBundle\Service\SubscribeCategorySyncService;

/**
 * @extends AbstractCrudController<SubscribeCategory>
 */
#[AdminCrud(routePath: '/wechat-mini-program-subscribe-message/subscribe-category', routeName: 'wechat_mini_program_subscribe_message_subscribe_category')]
final class WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly SubscribeCategorySyncService $syncService,
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return SubscribeCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('订阅消息类目')
            ->setEntityLabelInPlural('订阅消息类目')
            ->setPageTitle('index', '订阅消息类目')
            ->setPageTitle('detail', '订阅消息类目详情')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['categoryId', 'name'])
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

        yield IntegerField::new('categoryId', '类目ID');

        yield TextField::new('name', '类目名称')
            ->setMaxLength(30)
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('categoryId', '类目ID'))
            ->add(TextFilter::new('name', '类目名称'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $syncAction = Action::new('sync', '同步类目', 'fa fa-sync')
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
            $this->addFlash('success', "同步成功！共同步 {$syncCount} 个类目");
        } else {
            $this->addFlash('warning', "部分同步成功！共同步 {$syncCount} 个类目，{$errorCount} 个账号同步失败");
        }

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl()
        ;

        return new RedirectResponse($url);
    }
}
