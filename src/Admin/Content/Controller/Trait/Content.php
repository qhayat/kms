<?php

namespace Kms\Admin\Content\Controller\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Kms\Admin\Media\Builder\FinalUrlBuilder;
use Kms\Admin\Media\Entity\Media;

trait Content
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->overrideTemplate('crud/new', '@Kms/admin/content/new.html.twig')
            ->overrideTemplate('crud/edit', '@Kms/admin/content/edit.html.twig');
    }

    protected function buildFeaturedImageUrl(FinalUrlBuilder $finalUrlBuilder, ?Media $media): string
    {
        if (null !== $media) {
            return $finalUrlBuilder->build($media);
        }

        return '';
    }
}
