<?php

namespace WechatMiniProgramSubscribeMessageBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeParam;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateData;

#[When(env: 'test')]
#[When(env: 'dev')]
class SubscribeParamFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        $commonParams = [
            ['type' => SubscribeTemplateData::THING, 'code' => 'thing1', 'expression' => 'order.title', 'ref' => 'template-1'],
            ['type' => SubscribeTemplateData::TIME, 'code' => 'time2', 'expression' => 'order.createTime', 'ref' => 'template-2'],
            ['type' => SubscribeTemplateData::AMOUNT, 'code' => 'amount3', 'expression' => 'order.totalAmount', 'ref' => 'template-1'],
            ['type' => SubscribeTemplateData::PHRASE, 'code' => 'phrase4', 'expression' => 'order.status', 'ref' => 'template-2'],
            ['type' => SubscribeTemplateData::NAME, 'code' => 'name5', 'expression' => 'user.nickname', 'ref' => 'template-1'],
        ];

        foreach ($commonParams as $paramData) {
            $param = new SubscribeParam();
            $param->setType($paramData['type']);
            $param->setCode($paramData['code']);
            $param->setMapExpression($paramData['expression']);

            $template = $this->getReference($paramData['ref'], SubscribeTemplate::class);
            $param->setTemplate($template);

            if (SubscribeTemplateData::PHRASE === $paramData['type']) {
                $param->setEnumValues(['已支付', '已取消', '处理中', '已完成']);
            }

            $manager->persist($param);
        }

        $templateRefs = ['template-1', 'template-2'];
        for ($i = 0; $i < 8; ++$i) {
            $param = new SubscribeParam();
            $randomCase = $this->faker->randomElement(SubscribeTemplateData::cases());
            assert($randomCase instanceof SubscribeTemplateData);
            $param->setType($randomCase);
            $param->setCode($this->faker->unique()->regexify('[a-z]+[0-9]{3}'));
            $mapExpression = $this->faker->optional()->words(3, true);
            $param->setMapExpression(is_string($mapExpression) ? $mapExpression : null);

            $randomRef = $this->faker->randomElement($templateRefs);
            assert(is_string($randomRef));
            $template = $this->getReference($randomRef, SubscribeTemplate::class);
            $param->setTemplate($template);

            if ($this->faker->boolean(30)) {
                /** @var list<string> $enumValues */
                $enumValues = $this->faker->words($this->faker->numberBetween(2, 5));
                $param->setEnumValues($enumValues);
            }

            $manager->persist($param);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SubscribeTemplateFixtures::class,
        ];
    }
}
