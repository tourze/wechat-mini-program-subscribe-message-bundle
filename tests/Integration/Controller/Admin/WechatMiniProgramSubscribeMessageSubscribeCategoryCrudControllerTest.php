<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

class WechatMiniProgramSubscribeMessageSubscribeCategoryCrudControllerTest extends TestCase
{
    public function testGetEntityFqcn()
    {
        // Act
        $result = WechatMiniProgramSubscribeMessageSubscribeCategoryCrudController::getEntityFqcn();

        // Assert
        $this->assertEquals(SubscribeCategory::class, $result);
    }

}