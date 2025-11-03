<?php

namespace WechatMiniProgramSubscribeMessageBundle;

use BizUserBundle\Entity\BizUser;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle;
use HttpClientBundle\HttpClientBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\DoctrineResolveTargetEntityBundle\DependencyInjection\Compiler\ResolveTargetEntityPass;
use Tourze\Symfony\CronJob\CronJobBundle;
use Tourze\WechatMiniProgramAppIDContracts\MiniProgramInterface;
use Tourze\WechatMiniProgramUserContracts\UserInterface;
use WechatMiniProgramAuthBundle\WechatMiniProgramAuthBundle;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\WechatMiniProgramBundle;
use Tourze\EasyAdminMenuBundle\EasyAdminMenuBundle;

class WechatMiniProgramSubscribeMessageBundle extends Bundle implements BundleDependencyInterface
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new ResolveTargetEntityPass(UserInterface::class, BizUser::class),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1000,
        );

        $container->addCompilerPass(
            new ResolveTargetEntityPass(MiniProgramInterface::class, Account::class),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1000,
        );
    }

    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            CronJobBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
            HttpClientBundle::class => ['all' => true],
            TwigBundle::class => ['all' => true],
            SecurityBundle::class => ['all' => true],
            EasyAdminBundle::class => ['all' => true],
            WechatMiniProgramAuthBundle::class => ['all' => true],
            EasyAdminMenuBundle::class => ['all' => true],
        ];
    }
}
