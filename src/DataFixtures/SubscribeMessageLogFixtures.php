<?php

namespace WechatMiniProgramSubscribeMessageBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeMessageLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class SubscribeMessageLogFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 15; ++$i) {
            $log = new SubscribeMessageLog();
            $log->setTemplateId($this->faker->regexify('[A-Za-z0-9]{32}'));
            $log->setSubscribeStatus($this->generateSubscribeStatus());
            $log->setRawData($this->generateRawData());
            $log->setResultMsgId($this->faker->optional()->regexify('[A-Za-z0-9]{16}'));
            $log->setResultCode($this->faker->optional()->numberBetween(0, 50000));
            $resultStatus = $this->faker->optional()->randomElement(['success', 'failed', 'pending', 'cancelled']);
            $log->setResultStatus(is_string($resultStatus) ? $resultStatus : null);
            $log->setCreatedFromIp($this->faker->ipv4());

            $manager->persist($log);
        }

        $manager->flush();
    }

    private function generateSubscribeStatus(): string
    {
        $statuses = ['accept', 'reject', 'ban', 'pending'];
        $randomStatus = $this->faker->randomElement($statuses);
        assert(is_string($randomStatus));

        return $randomStatus;
    }

    private function generateRawData(): string
    {
        $data = [
            'ToUserName' => $this->faker->userName(),
            'FromUserName' => $this->faker->userName(),
            'CreateTime' => $this->faker->unixTime(),
            'MsgType' => 'event',
            'Event' => 'subscribe_msg_popup_event',
            'TemplateId' => $this->faker->regexify('[A-Za-z0-9]{32}'),
            'SubscribeStatusString' => $this->generateSubscribeStatus(),
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
