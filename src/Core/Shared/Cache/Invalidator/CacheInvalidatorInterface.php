<?php

namespace Kms\Core\Shared\Cache\Invalidator;

interface CacheInvalidatorInterface
{
    public function process(object $entity): void;
}
