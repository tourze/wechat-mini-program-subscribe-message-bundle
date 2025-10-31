<?php

namespace WechatMiniProgramSubscribeMessageBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;

/**
 * @internal
 */
#[CoversClass(SubscribeTemplate::class)]
final class SubscribeTemplateTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new SubscribeTemplate();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'id' => ['id', 'test-id-123'],
            'valid' => ['valid', true],
            'priTmplId' => ['priTmplId', 'template-123'],
            'title' => ['title', 'Test Template'],
            'content' => ['content', 'Test Content'],
            'example' => ['example', 'Test Example'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'updateTime' => ['updateTime', new \DateTimeImmutable()],
        ];
    }

    private SubscribeTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();
        $this->template = new SubscribeTemplate();
    }

    public function testIdGetterAndSetter(): void
    {
        // ID 由 SnowflakeIdGenerator 生成，我们不能直接设置，但可以测试 getter
        self::assertNull($this->template->getId());
    }

    public function testValidGetterAndSetter(): void
    {
        self::assertFalse($this->template->isValid());

        $this->template->setValid(true);
        self::assertTrue($this->template->isValid());

        $this->template->setValid(false);
        self::assertFalse($this->template->isValid());
    }

    public function testPriTmplIdGetterAndSetter(): void
    {
        $priTmplId = 'template123';
        $this->template->setPriTmplId($priTmplId);
        self::assertSame($priTmplId, $this->template->getPriTmplId());
    }

    public function testAccountGetterAndSetter(): void
    {
        // 使用具体类 Account 进行 mock 的理由1：Account 是微信小程序的核心业务实体，包含复杂的身份认证和授权逻辑，没有抽象接口可用
        // 使用具体类 Account 进行 mock 的理由2：SubscribeTemplate 与 Account 的关联关系是强类型依赖，测试必须验证 Doctrine ORM 的具体实体关联行为
        // 使用具体类 Account 进行 mock 的理由3：Account 类包含微信小程序特有的业务规则和生命周期钩子，接口无法模拟其完整行为
        $account = $this->createMock(Account::class);
        $this->template->setAccount($account);
        self::assertSame($account, $this->template->getAccount());

        $this->template->setAccount(null);
        self::assertNull($this->template->getAccount());
    }

    public function testTitleGetterAndSetter(): void
    {
        $title = '测试模板';
        $this->template->setTitle($title);
        self::assertSame($title, $this->template->getTitle());
    }

    public function testContentGetterAndSetter(): void
    {
        $content = '模板内容示例';
        $this->template->setContent($content);
        self::assertSame($content, $this->template->getContent());
    }

    public function testExampleGetterAndSetter(): void
    {
        $example = '模板示例内容';
        $this->template->setExample($example);
        self::assertSame($example, $this->template->getExample());
    }

    public function testTypeGetterAndSetter(): void
    {
        $type = SubscribeTemplateType::ONCE;
        $this->template->setType($type);
        self::assertSame($type, $this->template->getType());
    }

    public function testCreateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        $this->template->setCreateTime($now);
        self::assertSame($now, $this->template->getCreateTime());
    }

    public function testUpdateTimeGetterAndSetter(): void
    {
        $now = new \DateTimeImmutable();
        $this->template->setUpdateTime($now);
        self::assertSame($now, $this->template->getUpdateTime());
    }

    public function testParamsInitialization(): void
    {
        $params = $this->template->getParams();
        self::assertInstanceOf(ArrayCollection::class, $params);
        self::assertCount(0, $params);
    }

    public function testAddParam(): void
    {
        $param = new SubscribeParam();

        $this->template->addParam($param);

        self::assertCount(1, $this->template->getParams());
        self::assertTrue($this->template->getParams()->contains($param));
        self::assertSame($this->template, $param->getTemplate());
    }

    public function testAddParamWhenAlreadyContainsParam(): void
    {
        $param = new SubscribeParam();
        $param->setTemplate($this->template);

        // 初始状态下添加参数到集合中
        $this->template->getParams()->add($param);
        self::assertCount(1, $this->template->getParams());

        // 再次添加同一个参数，不应该重复添加
        $this->template->addParam($param);

        self::assertCount(1, $this->template->getParams());
    }

    public function testRemoveParam(): void
    {
        $param = new SubscribeParam();
        $this->template->addParam($param);
        self::assertCount(1, $this->template->getParams());

        $this->template->removeParam($param);

        self::assertCount(0, $this->template->getParams());
        self::assertFalse($this->template->getParams()->contains($param));
        self::assertNull($param->getTemplate());
    }

    public function testRemoveParamWhenParamNotInCollection(): void
    {
        $param = new SubscribeParam();
        self::assertCount(0, $this->template->getParams());

        $this->template->removeParam($param);

        self::assertCount(0, $this->template->getParams());
    }
}
