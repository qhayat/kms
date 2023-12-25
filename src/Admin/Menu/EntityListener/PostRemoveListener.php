<?php

namespace Kms\Admin\Menu\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Kms\Admin\Menu\Entity\Menu;
use Kms\Admin\Menu\Entity\MenuItem;
use Kms\Core\Shared\Cache\Invalidator\CacheInvalidatorInterface;

#[AsEntityListener(event: Events::postRemove, method: 'listen', entity: Menu::class)]
#[AsEntityListener(event: Events::postRemove, method: 'listen', entity: MenuItem::class)]
class PostRemoveListener
{
    public function __construct(private readonly CacheInvalidatorInterface $menuCacheInvalidator)
    {
    }

    public function listen(Menu|MenuItem $entity): void
    {
        if (null !== $menu = $entity instanceof Menu ? $entity : $entity->getMenu()) {
            $this->menuCacheInvalidator->process($menu);
        }
    }
}
