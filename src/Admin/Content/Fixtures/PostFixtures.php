<?php

namespace Kms\Admin\Content\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Kms\Admin\User\Entity\User;
use Kms\Admin\User\Fixtures\UserFixtures;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Shared\Constant\FixturesConstant;

class PostFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        /**
         * @var User $user
         */
        $user = $userRepository->findOneBy(['email' => 'j.doe@example.local']);

        for ($i = 0; $i < 20; ++$i) {
            $post = (new Post())
                ->setTitle('Post '.$i)
                ->setSlug('post-'.$i)
                ->setExcerpt('Post '.$i.' excerpt')
                ->setContent('Welcome to the post '.$i.'!')
                ->setStatus(Status::PUBLISHED)
                ->setCreatedAt((new \DateTime())->modify(sprintf('+%s minutes', $i)))
                ->setUpdatedAt((new \DateTime())->modify(sprintf('+%s minutes', $i)))
                ->setAuthor($user)
            ;

            $manager->persist($post);
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
