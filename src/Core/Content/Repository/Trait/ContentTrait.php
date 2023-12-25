<?php

namespace Kms\Core\Content\Repository\Trait;

use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Enum\Status;

trait ContentTrait
{
    public function findOneBySlug(string $slug, Status $status = Status::PUBLISHED): ?Content
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.slug = :slug')
            ->andWhere('c.status = :status')
            ->setParameter('slug', $slug)
            ->setParameter('status', $status)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countBySlug(string $slug, string $excludeId = null): int
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.slug = :slug')
            ->setParameter('slug', $slug);

        if ($excludeId) {
            $queryBuilder
                ->andWhere('c.id != :id')
                ->setParameter('id', $excludeId);
        }

        return $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }
}
