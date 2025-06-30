<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Integration\Controller\Admin;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramSubscribeMessageBundle\Controller\Admin\WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;

class WechatMiniProgramSubscribeMessageSubscribeTemplateCrudControllerTest extends TestCase
{
    public function testGetEntityFqcn()
    {
        // Act
        $result = WechatMiniProgramSubscribeMessageSubscribeTemplateCrudController::getEntityFqcn();

        // Assert
        $this->assertEquals(SubscribeTemplate::class, $result);
    }

}