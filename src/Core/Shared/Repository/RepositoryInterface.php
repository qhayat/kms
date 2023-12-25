<?php

namespace Kms\Core\Shared\Repository;

use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;

interface RepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function total(array $filters = []): int;

    public function paginate(int $page = 1, int $maxResult = 10, array $sorts = [], array $filters = []): array;
}
