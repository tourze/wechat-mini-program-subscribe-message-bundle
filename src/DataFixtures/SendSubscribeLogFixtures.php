<?php

namespace WechatMiniProgramSubscribeMessageBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramSubscribeMessageBundle\Entity\SendSubscribeLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class SendSubscribeLogFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $log = new SendSubscribeLog();
            $log->setTemplateId($this->faker->regexify('[A-Za-z0-9]{32}'));
            $log->setResult($this->generateSendResult());
            $log->setRemark($this->faker->optional()->sentence());
            $log->setCreatedFromIp($this->faker->ipv4());

            $manager->persist($log);
        }

        $manager->flush();
    }

    private function generateSendResult(): string
    {
        $results = [
            '{"errcode":0,"errmsg":"ok","msgid":"12345"}',
            '{"errcode":43101,"errmsg":"user refuse to receive the msg"}',
            '{"errcode":47003,"errmsg":"argument is required"}',
            '{"errcode":41028,"errmsg":"invalid form_id"}',
        ];

        $randomResult = $this->faker->randomElement($results);
        assert(is_string($randomResult));

        return $randomResult;
    }
}
