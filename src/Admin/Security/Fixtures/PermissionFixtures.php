<?php

namespace Kms\Admin\Security\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Core\Secutiry\Entity\Permission;
use Kms\Core\Shared\Constant\FixturesConstant;

class PermissionFixtures extends Fixture implements FixtureGroupInterface
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

    public static function getGroups(): array
    {
        return [
            FixturesConstant::KMS_DEMO,
            FixturesConstant::KMS_STRICTLY_NECESSARY,
        ];
    }
}
