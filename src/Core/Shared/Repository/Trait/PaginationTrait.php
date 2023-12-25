<?php

namespace Kms\Core\Shared\Repository\Trait;

use Doctrine\ORM\QueryBuilder;
use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;

trait PaginationTrait
{
    public function paginate(int $page = 1, int $maxResult = 10, array $sort = [], array $filter = []): array
    {
        $query = $this->createQueryBuilder('r')
            ->setMaxResults($maxResult)
            ->setFirstResult(($page - 1) * $maxResult);

        $this->applyFilterOnQuery($query, $filter);
        $this->applySortOnQuery($query, $sort);

        return $query
            ->getQuery()
            ->getResult();
    }

    public function total(array $filters = []): int
    {
        $query = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)');

        $this->applyFilterOnQuery($query, $filters);

        return $query
            ->getQuery()
            ->getSingleScalarResult();
    }

    protected function applySortOnQuery(QueryBuilder $queryBuilder, array $sort): void
    {
        foreach ($sort as $key => $value) {
            $queryBuilder->orderBy(sprintf('r.%s', $key), $value);
        }
    }

    protected function applyFilterOnQuery(QueryBuilder $queryBuilder, array $filter): void
    {
        foreach ($filter as $key => $value) {
            $queryBuilder
                ->andWhere(sprintf('r.%s = :%s', $key, $key))
                ->setParameter($key, $value);
        }
    }
}
