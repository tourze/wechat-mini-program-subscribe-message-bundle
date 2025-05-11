<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;

class SubscribeTemplateTest extends TestCase
{
    private SubscribeTemplate $template;

    protected function setUp(): void
    {
        $this->template = new SubscribeTemplate();
    }

    public function testIdGetterAndSetter()
    {
        // ID 由 SnowflakeIdGenerator 生成，我们不能直接设置，但可以测试 getter
        $this->assertNull($this->template->getId());
    }

    public function testValidGetterAndSetter()
    {
        $this->assertFalse($this->template->isValid());

        $this->template->setValid(true);
        $this->assertTrue($this->template->isValid());

        $this->template->setValid(false);
        $this->assertFalse($this->template->isValid());
    }

    public function testPriTmplIdGetterAndSetter()
    {
        $priTmplId = 'template123';
        $this->template->setPriTmplId($priTmplId);
        $this->assertSame($priTmplId, $this->template->getPriTmplId());
    }

    public function testAccountGetterAndSetter()
    {
        $account = $this->createMock(Account::class);
        $this->template->setAccount($account);
        $this->assertSame($account, $this->template->getAccount());

        $this->template->setAccount(null);
        $this->assertNull($this->template->getAccount());
    }

    public function testTitleGetterAndSetter()
    {
        $title = '测试模板';
        $this->template->setTitle($title);
        $this->assertSame($title, $this->template->getTitle());
    }

    public function testContentGetterAndSetter()
    {
        $content = '模板内容示例';
        $this->template->setContent($content);
        $this->assertSame($content, $this->template->getContent());
    }

    public function testExampleGetterAndSetter()
    {
        $example = '模板示例内容';
        $this->template->setExample($example);
        $this->assertSame($example, $this->template->getExample());
    }

    public function testTypeGetterAndSetter()
    {
        $type = SubscribeTemplateType::ONCE;
        $this->template->setType($type);
        $this->assertSame($type, $this->template->getType());
    }

    public function testCreateTimeGetterAndSetter()
    {
        $now = new \DateTime();
        $this->template->setCreateTime($now);
        $this->assertSame($now, $this->template->getCreateTime());
    }

    public function testUpdateTimeGetterAndSetter()
    {
        $now = new \DateTime();
        $this->template->setUpdateTime($now);
        $this->assertSame($now, $this->template->getUpdateTime());
    }

    public function testParamsInitialization()
    {
        $params = $this->template->getParams();
        $this->assertInstanceOf(ArrayCollection::class, $params);
        $this->assertCount(0, $params);
    }

    public function testAddParam()
    {
        $param = new SubscribeParam();

        $result = $this->template->addParam($param);

        $this->assertSame($this->template, $result);
        $this->assertCount(1, $this->template->getParams());
        $this->assertTrue($this->template->getParams()->contains($param));
        $this->assertSame($this->template, $param->getTemplate());
    }

    public function testAddParam_whenAlreadyContainsParam()
    {
        $param = new SubscribeParam();
        $param->setTemplate($this->template);

        // 初始状态下添加参数到集合中
        $this->template->getParams()->add($param);
        $this->assertCount(1, $this->template->getParams());

        // 再次添加同一个参数，不应该重复添加
        $result = $this->template->addParam($param);

        $this->assertSame($this->template, $result);
        $this->assertCount(1, $this->template->getParams());
    }

    public function testRemoveParam()
    {
        $param = new SubscribeParam();
        $this->template->addParam($param);
        $this->assertCount(1, $this->template->getParams());

        $result = $this->template->removeParam($param);

        $this->assertSame($this->template, $result);
        $this->assertCount(0, $this->template->getParams());
        $this->assertFalse($this->template->getParams()->contains($param));
        $this->assertNull($param->getTemplate());
    }

    public function testRemoveParam_whenParamNotInCollection()
    {
        $param = new SubscribeParam();
        $this->assertCount(0, $this->template->getParams());

        $result = $this->template->removeParam($param);

        $this->assertSame($this->template, $result);
        $this->assertCount(0, $this->template->getParams());
    }
}
