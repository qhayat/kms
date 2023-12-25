<?php

namespace Kms\Admin\Setting\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kms\Admin\Setting\Entity\Setting;
use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;
use Kms\Core\Shared\Repository\RepositoryInterface;
use Kms\Core\Shared\Repository\Trait\PaginationTrait;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Setting>
 *
 * @implements PasswordUpgraderInterface<Setting>
 *
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository implements RepositoryInterface
{
    use PaginationTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function toArray(): array
    {
        $settings = [];
        foreach ($this->findAll() as $setting) {
            $settings[$setting->getKey()] = $setting->getValue();
        }

        return $settings;
    }
}
