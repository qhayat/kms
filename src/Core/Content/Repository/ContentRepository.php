<?php

namespace Kms\Core\Content\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Content\Repository\Trait\ContentTrait;
use Kms\Core\Shared\Repository\Trait\PaginationTrait;

/**
 * @extends ServiceEntityRepository<Content>
 *
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository implements ContentRepositoryInterface
{
    use PaginationTrait;
    use ContentTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }
}
