<?php

namespace Kms\Admin\Content\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Kms\Admin\Content\Service\CheckAndGenerateUniqueSlug;
use Kms\Admin\Media\Factory\CreateFromString as CreateMediaFromString;
use Kms\Admin\User\Entity\User;
use Kms\Core\Content\Entity\Content;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'listen', entity: Content::class)]
class PrePersistListener
{
    public function __construct(
        private readonly CheckAndGenerateUniqueSlug $checkAndGenerateUniqueSlug,
        private readonly Security $security,
        private readonly CreateMediaFromString $createMediaFromString,
    ) {
    }

    public function listen(Content $content): void
    {
        if (null === $content->getAuthor()) {
            /**
             * @var User $user
             */
            $user = $this->security->getUser();
            $content->setAuthor($user);
        }

        if ($content->getImage()) {
            $content->setFeaturedImage(
                $this->createMediaFromString->create($content->getImage())
            );
        }

        $this->checkAndGenerateUniqueSlug->process($content);
    }
}
