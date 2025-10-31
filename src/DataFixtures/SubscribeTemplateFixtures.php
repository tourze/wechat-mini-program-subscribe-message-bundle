<?php

namespace WechatMiniProgramSubscribeMessageBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeTemplate;
use WechatMiniProgramSubscribeMessageBundle\Enum\SubscribeTemplateType;

#[When(env: 'test')]
#[When(env: 'dev')]
class SubscribeTemplateFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        $templates = [
            [
                'priTmplId' => 'AT0002',
                'title' => '订单支付成功通知',
                'content' => '您的订单{{thing1.DATA}}已支付成功，支付时间{{time2.DATA}}，支付金额{{amount3.DATA}}',
                'example' => '您的订单iPhone 14已支付成功，支付时间2024-01-15 10:30:00，支付金额5999.00元',
                'type' => SubscribeTemplateType::ONCE,
            ],
            [
                'priTmplId' => 'AT0003',
                'title' => '课程开始提醒',
                'content' => '您报名的课程{{thing1.DATA}}将于{{time2.DATA}}开始，请准时参加',
                'example' => '您报名的课程PHP高级开发将于2024-01-15 14:00:00开始，请准时参加',
                'type' => SubscribeTemplateType::ONCE,
            ],
            [
                'priTmplId' => 'AT0004',
                'title' => '会员到期提醒',
                'content' => '您的{{phrase1.DATA}}会员将于{{time2.DATA}}到期，请及时续费',
                'example' => '您的VIP会员将于2024-02-15 23:59:59到期，请及时续费',
                'type' => SubscribeTemplateType::LONG,
            ],
        ];

        foreach ($templates as $index => $templateData) {
            $template = new SubscribeTemplate();
            $template->setPriTmplId($templateData['priTmplId']);
            $template->setTitle($templateData['title']);
            $template->setContent($templateData['content']);
            $template->setExample($templateData['example']);
            $template->setType($templateData['type']);
            $template->setValid($this->faker->boolean(80));

            $manager->persist($template);

            if ($index < 2) {
                $this->addReference('template-' . ($index + 1), $template);
            }
        }

        for ($i = 0; $i < 7; ++$i) {
            $template = new SubscribeTemplate();
            $template->setPriTmplId($this->faker->regexify('AT[0-9]{4}'));
            $template->setTitle($this->faker->sentence(4));
            $template->setContent($this->generateTemplateContent());
            $template->setExample($this->generateTemplateExample());
            $randomType = $this->faker->randomElement(SubscribeTemplateType::cases());
            assert($randomType instanceof SubscribeTemplateType);
            $template->setType($randomType);
            $template->setValid($this->faker->boolean(70));

            $manager->persist($template);
        }

        $manager->flush();
    }

    private function generateTemplateContent(): string
    {
        $patterns = [
            '您的订单{{thing1.DATA}}已{{phrase2.DATA}}，时间{{time3.DATA}}',
            '{{name1.DATA}}，您的{{thing2.DATA}}服务将于{{time3.DATA}}到期',
            '恭喜{{name1.DATA}}，您已成功{{phrase2.DATA}}，金额{{amount3.DATA}}',
            '提醒：{{thing1.DATA}}预约时间{{time2.DATA}}，请准时参加',
        ];

        $randomPattern = $this->faker->randomElement($patterns);
        assert(is_string($randomPattern));

        return $randomPattern;
    }

    private function generateTemplateExample(): string
    {
        $examples = [
            '您的订单iPhone 15已发货，时间2024-01-15 09:30:00',
            '张三，您的会员服务将于2024-02-28 23:59:59到期',
            '恭喜李四，您已成功充值，金额100.00元',
            '提醒：医院体检预约时间2024-01-20 08:00:00，请准时参加',
        ];

        $randomExample = $this->faker->randomElement($examples);
        assert(is_string($randomExample));

        return $randomExample;
    }
}
