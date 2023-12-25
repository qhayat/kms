<?php

namespace Kms\Core\Security\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Kms\Core\Secutiry\Entity\Permission;

class PermissionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $pageReadPermission = (new Permission())
            ->setKey('PAGE:READ')
            ->setDescription('Allow to read pages');

        $postReadPermission = (new Permission())
            ->setKey('POST:READ')
            ->setDescription('Allow to read post');

        $manager->persist($pageReadPermission);
        $manager->persist($postReadPermission);
        $manager->flush();
    }
}
