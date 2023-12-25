<?php

namespace Kms\Admin\Menu\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\Menu\Entity\Menu;
use Kms\Admin\Menu\Entity\MenuItem;
use Kms\Core\Shared\Constant\FixturesConstant;

class MenuFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $menu = (new Menu())
            ->setName('main');
        $menu->addMenuItem((new MenuItem())
            ->setLabel('Home')
            ->setUrl('/')
        );
        $menu->addMenuItem((new MenuItem())
            ->setLabel('About')
            ->setUrl('/about')
        );
        $menu->addMenuItem((new MenuItem())
            ->setLabel('Blog')
            ->setUrl('/blog')
        );
        $menu->addMenuItem((new MenuItem())
            ->setLabel('Contact')
            ->setUrl('/contact')
        );

        $manager->persist($menu);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            FixturesConstant::KMS_DEMO
        ];
    }
}
