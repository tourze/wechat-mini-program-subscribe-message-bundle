<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

class WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudControllerTest extends TestCase
{
    public function testGetEntityFqcn()
    {
        // Act
        $result = WechatMiniProgramSubscribeMessageSubscribeMessageLogCrudController::getEntityFqcn();

        // Assert
        $this->assertEquals(SubscribeMessageLog::class, $result);
    }

}