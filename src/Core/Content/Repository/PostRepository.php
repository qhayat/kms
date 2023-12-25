<?php

namespace Kms\Core\Content\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\Repository\Trait\ContentTrait;
use Kms\Core\Shared\Repository\Trait\PaginationTrait;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements ContentRepositoryInterface
{
    use PaginationTrait;
    use ContentTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function byCategory(string $categoryName): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->andWhere('c.name = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array<int, string> $categoriesName
     *
     * @return array<int, Post>
     */
    public function byCategories(array $categoriesName): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->andWhere('c.name IN (:categoriesName)')
            ->setParameter('categoriesName', $categoriesName)
            ->getQuery()
            ->getResult();
    }
}
