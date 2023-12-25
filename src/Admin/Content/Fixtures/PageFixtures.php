<?php

namespace Kms\Admin\Content\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\User\Entity\User;
use Kms\Admin\User\Fixtures\UserFixtures;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Shared\Constant\FixturesConstant;

class PageFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const PAGES = [
        [
            'title' => 'KMS',
            'slug' => 'home',
            'isHomePage' => true,
            'content' => 'Welcome to the home page!',
            'status' => Status::PUBLISHED,
        ],
        [
            'title' => 'About',
            'slug' => 'about',
            'content' => 'Welcome to the about page!',
            'status' => Status::PUBLISHED,
        ],
        [
            'title' => 'Blog',
            'slug' => 'blog',
            'isBlogPage' => true,
            'content' => 'Welcome to the blog page!',
            'status' => Status::PUBLISHED,
        ],
        [
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => 'Welcome to the contact page!',
            'status' => Status::PUBLISHED,
        ],
        [
            'title' => 'Unpublished',
            'slug' => 'unpublished',
            'content' => 'Welcome to the unpublished page!',
            'status' => Status::DRAFT,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        /**
         * @var User $user
         */
        $user = $userRepository->findOneBy(['email' => 'j.doe@example.local']);

        foreach (self::PAGES as $pageData) {
            $page = (new Page())
                ->setTitle($pageData['title'])
                ->setSlug($pageData['slug'])
                ->setIsHomePage($pageData['isHomePage'] ?? false)
                ->setIsBlogPage($pageData['isBlogPage'] ?? false)
                ->setContent($pageData['content'])
                ->setStatus($pageData['status'])
                ->setAuthor($user)
            ;

            $manager->persist($page);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return FixturesConstant::GROUPS;
    }
}
