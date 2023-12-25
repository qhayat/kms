<?php

namespace Kms\Core\Content\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Content\Repository\Trait\ContentTrait;
use Kms\Core\Shared\Repository\Trait\PaginationTrait;

/**
 * @extends ServiceEntityRepository<Page>
 *
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository implements ContentRepositoryInterface
{
    use PaginationTrait;
    use ContentTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findHomePage(): ?Page
    {
        return $this->findOneBy([
            'status' => Status::PUBLISHED, 'homePage' => true,
        ]);
    }

    public function findBlogPage(): ?Page
    {
        return $this->findOneBy([
            'status' => Status::PUBLISHED, 'blogPage' => true,
        ]);
    }
}
