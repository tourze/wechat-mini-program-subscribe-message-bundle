<?php

namespace WechatMiniProgramSubscribeMessageBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramSubscribeMessageBundle\Entity\SubscribeCategory;

#[When(env: 'test')]
#[When(env: 'dev')]
class SubscribeCategoryFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('zh_CN');
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['id' => 1, 'name' => '电商购物'],
            ['id' => 2, 'name' => '商家服务'],
            ['id' => 3, 'name' => '生活服务'],
            ['id' => 4, 'name' => '教育培训'],
            ['id' => 5, 'name' => '医疗健康'],
            ['id' => 6, 'name' => '金融理财'],
            ['id' => 7, 'name' => '房地产'],
            ['id' => 8, 'name' => '交通运输'],
        ];

        foreach ($categories as $categoryData) {
            $category = new SubscribeCategory();
            $category->setCategoryId($categoryData['id']);
            $category->setName($categoryData['name']);

            $manager->persist($category);
        }

        for ($i = 0; $i < 5; ++$i) {
            $category = new SubscribeCategory();
            $category->setCategoryId($this->faker->numberBetween(100, 999));
            $words = $this->faker->words(2, true);
            $category->setName(is_string($words) ? $words : implode(' ', $words));

            $manager->persist($category);
        }

        $manager->flush();
    }
}
