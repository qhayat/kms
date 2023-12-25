<?php

namespace Kms\Admin\Menu\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Kms\Admin\Menu\Entity\MenuItem;
use Kms\Core\Shared\Cache\Invalidator\CacheInvalidatorInterface;

#[AsEntityListener(event: Events::postPersist, method: 'listen', entity: MenuItem::class)]
class PostPersistListener
{
    public function __construct(private readonly CacheInvalidatorInterface $menuCacheInvalidator)
    {
    }

    public function listen(MenuItem $entity): void
    {
        if (null !== $menu = $entity->getMenu()) {
            $this->menuCacheInvalidator->process($menu);
        }
    }
}
