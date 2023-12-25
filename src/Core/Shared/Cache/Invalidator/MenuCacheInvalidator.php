<?php

namespace Kms\Core\Shared\Cache\Invalidator;

use Kms\Admin\Menu\Entity\Menu;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class MenuCacheInvalidator implements CacheInvalidatorInterface
{
    public function __construct(
        private readonly TagAwareAdapterInterface $kmsCache,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function process(object $entity): void
    {
        if (!$entity instanceof Menu) {
            return;
        }

        try {
            $menu = $entity;
            if (!$this->kmsCache->invalidateTags([sprintf('menu_%s', $menu->getName())])) {
                $this->logger->error(sprintf('Cache invalidation failed for menu %s', $menu->getName()));
            }
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
