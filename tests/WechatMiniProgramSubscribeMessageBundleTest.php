<?php

declare(strict_types=1);

namespace WechatMiniProgramSubscribeMessageBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramSubscribeMessageBundle\DependencyInjection\WechatMiniProgramSubscribeMessageExtension;
use WechatMiniProgramSubscribeMessageBundle\WechatMiniProgramSubscribeMessageBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramSubscribeMessageBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramSubscribeMessageBundleTest extends AbstractBundleTestCase
{
}
