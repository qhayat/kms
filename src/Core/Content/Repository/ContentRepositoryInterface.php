<?php

namespace Kms\Core\Content\Repository;

use Kms\Core\Content\Entity\Content;
use Kms\Core\Shared\Repository\RepositoryInterface;

interface ContentRepositoryInterface extends RepositoryInterface
{
    public function findOneBySlug(string $slug): ?Content;

    public function countBySlug(string $slug, string $excludeId = null): int;
}
