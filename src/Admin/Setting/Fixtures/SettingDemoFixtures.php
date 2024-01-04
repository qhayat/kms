<?php

namespace Kms\Admin\Setting\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\Content\Fixtures\PageFixtures;
use Kms\Admin\Setting\Entity\Setting;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Shared\Constant\FixturesConstant;

class SettingDemoFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pageRepository = $manager->getRepository(Page::class);
        $settingRepository = $manager->getRepository(Setting::class);

        $homePage = $pageRepository->findOneBy(['homePage' => true]);
        $blogPage = $pageRepository->findOneBy(['blogPage' => true]);

        $homePageSetting = $settingRepository->findOneBy(['key' => 'home_page']);
        $homePageSetting->setValue($homePage->getId());
        $manager->persist($homePageSetting);

        $blogPageSetting = $settingRepository->findOneBy(['key' => 'blog_page']);
        $blogPageSetting->setValue($blogPage->getId());
        $manager->persist($blogPageSetting);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
            SettingFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return [
            FixturesConstant::KMS_DEMO,
        ];
    }
}
