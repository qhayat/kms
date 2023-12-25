<?php

namespace Kms\Admin\Menu;

use Kms\Admin\Menu\Entity\MenuItem;
use Kms\Admin\Menu\Repository\MenuRepository;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class Menu
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
        private readonly TagAwareAdapterInterface $kmsCache,
    ) {
    }

    /**
     * @return MenuItem[]
     */
    public function get(string $name): array
    {
        $cacheItem = $this->kmsCache->getItem(sprintf('menu_%s', $name));

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        if (null === $menu = $this->menuRepository->findOneBy(['name' => $name])) {
            return [];
        }

        $menuItems = $menu->getMenuItems()->toArray();
        $cacheItem->set($menuItems);
        $cacheItem->tag([
            'menu',
            sprintf('menu_%s', $name),
        ]);
        $this->kmsCache->save($cacheItem);

        return $menuItems;
    }
}
