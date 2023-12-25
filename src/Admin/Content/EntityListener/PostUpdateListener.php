<?php

namespace Kms\Admin\Content\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Shared\Cache\Invalidator\CacheInvalidatorInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'listen', entity: Page::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'listen', entity: Post::class)]
class PostUpdateListener
{
    public function __construct(private readonly CacheInvalidatorInterface $contentCacheInvalidator)
    {
    }

    public function listen(Content $content): void
    {
        $this->contentCacheInvalidator->process($content);
    }
}
