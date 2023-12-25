<?php

namespace Kms\Admin\User\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\User\Entity\User;
use Kms\Core\Shared\Constant\FixturesConstant;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const USERS = [
        [
            'firstName' => 'John',
            'lastName' => 'DOE',
            'email' => 'j.doe@example.local',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'P@ssword1',
        ],
        [
            'firstName' => 'Jean',
            'lastName' => 'YVE',
            'email' => 'j.yve@example.local',
            'roles' => ['ROLE_USER'],
            'password' => 'P@ssword2',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userData) {
            $user = (new User())
                ->setFirstName($userData['firstName'])
                ->setLastName($userData['lastName'])
                ->setEmail($userData['email'])
                ->setRoles($userData['roles'])
                ->setPassword($userData['password']);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return FixturesConstant::GROUPS;
    }
}
