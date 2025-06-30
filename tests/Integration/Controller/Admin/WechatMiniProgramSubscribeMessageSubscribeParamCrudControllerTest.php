<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeParamCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;

class WechatMiniProgramSubscribeMessageSubscribeParamCrudControllerTest extends TestCase
{
    public function testGetEntityFqcn()
    {
        // Act
        $result = WechatMiniProgramSubscribeMessageSubscribeParamCrudController::getEntityFqcn();

        // Assert
        $this->assertEquals(SubscribeParam::class, $result);
    }

}