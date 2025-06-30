<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

class WechatMiniProgramSubscribeMessageSendSubscribeLogCrudControllerTest extends TestCase
{
    public function testGetEntityFqcn()
    {
        // Act
        $result = WechatMiniProgramSubscribeMessageSendSubscribeLogCrudController::getEntityFqcn();

        // Assert
        $this->assertEquals(SendSubscribeLog::class, $result);
    }

}