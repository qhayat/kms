<?php

namespace Kms\Admin\Content\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Kms\Admin\Content\Service\CheckAndGenerateUniqueSlug;
use Kms\Admin\Media\Factory\CreateFromString as CreateMediaFromString;
use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;

#[AsEntityListener(event: Events::preUpdate, method: 'listen', entity: Page::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'listen', entity: Post::class)]
class PreUpdateListener
{
    public function __construct(
        private readonly CheckAndGenerateUniqueSlug $checkAndGenerateUniqueSlug,
        private readonly CreateMediaFromString $createMediaFromString,
    ) {
    }

    public function listen(Content $content, EventArgs $args): void
    {
        $this->checkAndGenerateUniqueSlug->process($content);

        if ($content->getImage()) {
            $content->setFeaturedImage(
                $this->createMediaFromString->create($content->getImage())
            );

            $em = $args->getEntityManager();
            $em->persist($content);
            $em->flush();
        }
    }
}
