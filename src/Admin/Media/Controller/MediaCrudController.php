<?php

namespace Kms\Admin\Media\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Kms\Admin\Media\Builder\FinalUrlBuilder;
use Kms\Admin\Media\Entity\Media;

class MediaCrudController extends AbstractCrudController
{
    public function __construct(private readonly FinalUrlBuilder $kmsMediaFinalUrlBuilder)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('name'),
            ImageField::new('image')
                ->setBasePath('')
                ->formatValue(function ($value, $entity) {
                   return $this->kmsMediaFinalUrlBuilder->build($entity);
                })
                ->setRequired(false),
        ];
    }
}
