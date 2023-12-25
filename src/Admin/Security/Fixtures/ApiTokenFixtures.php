<?php

namespace Kms\Admin\Security\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Core\Security\Entity\ApiToken;
use Kms\Core\Secutiry\Entity\Permission;
use Kms\Core\Shared\Constant\FixturesConstant;

class ApiTokenFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $permissionRepository = $manager->getRepository(Permission::class);
        $pageReadPermission = $permissionRepository->findOneBy(['key' => 'PAGE:READ']);

        $apiToken = (new ApiToken())
            ->setName('API Token for example')
            ->setEnabled(true)
            ->setToken(substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', 32)), 0, 32))
            ->addPermission($pageReadPermission);

        $manager->persist($apiToken);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PermissionFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return [
            FixturesConstant::KMS_DEMO
        ];
    }
}
