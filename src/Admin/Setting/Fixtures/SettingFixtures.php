<?php

namespace Kms\Admin\Setting\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\Content\Fixtures\PageFixtures;
use Kms\Admin\Setting\Entity\Setting;
use Kms\Admin\User\Entity\User;
use Kms\Admin\User\Fixtures\UserFixtures;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Shared\Constant\FixturesConstant;

class SettingFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $pageRepository = $manager->getRepository(Page::class);
        $userRepository = $manager->getRepository(User::class);

        $homePage = $pageRepository->findOneBy(['homePage' => true]);
        $blogPage = $pageRepository->findOneBy(['blogPage' => true]);
        $author = $userRepository->findOneBy(['email' => 'j.doe@example.local']);

        $homePageSetting = (new Setting())
            ->setKey('home_page')
            ->setValue($homePage->getId())
            ->setAuthor($author);
        $manager->persist($homePageSetting);

        $blogPageSetting = (new Setting())
            ->setKey('blog_page')
            ->setValue($blogPage->getId())
            ->setAuthor($author);
        $manager->persist($blogPageSetting);

        $siteNameSetting = (new Setting())
            ->setKey('site_name')
            ->setValue('KMS')
            ->setAuthor($author);
        $manager->persist($siteNameSetting);

        $siteDescriptionSetting = (new Setting())
            ->setKey('site_description')
            ->setValue('KMS is a simple CMS')
            ->setAuthor($author);
        $manager->persist($siteDescriptionSetting);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PageFixtures::class,
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return [
            FixturesConstant::KMS_DEMO,
            FixturesConstant::KMS_STRICTLY_NECESSARY,
        ];
    }
}
