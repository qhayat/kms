<?php

namespace Kms\Admin\User\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Kms\Admin\User\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'listen', entity: User::class)]
class PrePersistListener
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function listen(User $user): void
    {
        $password = $this->hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);
    }
}
