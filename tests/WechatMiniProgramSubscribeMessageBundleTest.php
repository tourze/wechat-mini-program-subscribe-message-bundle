<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\Symfony\CronJob\CronJobBundle;
use WechatMiniProgramSubscribeMessageBundle\WechatMiniProgramSubscribeMessageBundle;

class WechatMiniProgramSubscribeMessageBundleTest extends TestCase
{
    public function testBundleDependencies()
    {
        $dependencies = WechatMiniProgramSubscribeMessageBundle::getBundleDependencies();
        
        $this->assertIsArray($dependencies);
        $this->assertCount(2, $dependencies);
        $this->assertArrayHasKey(DoctrineIndexedBundle::class, $dependencies);
        $this->assertArrayHasKey(CronJobBundle::class, $dependencies);
        $this->assertSame(['all' => true], $dependencies[DoctrineIndexedBundle::class]);
        $this->assertSame(['all' => true], $dependencies[CronJobBundle::class]);
    }
} 