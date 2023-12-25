<?php

namespace Kms\Admin\Content\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Kms\Admin\Content\Controller\Trait\Content as ContentTrait;
use Kms\Admin\Media\Builder\FinalUrlBuilder;
use Kms\Admin\Media\Uploader\UploaderInterface;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Enum\Status;

class PageCrudController extends AbstractCrudController
{
    use ContentTrait;

    public function __construct(
        private readonly UploaderInterface $kmsUploader,
        private readonly FinalUrlBuilder $kmsMediaFinalUrlBuilder,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('slug')
                ->onlyOnForms(),
            TextEditorField::new('excerpt')->onlyOnForms(),
            TextEditorField::new('content')->onlyOnForms(),
            ChoiceField::new('status')
                ->setChoices([
                    'Draft' => Status::DRAFT,
                    'Published' => Status::PUBLISHED,
                    'Archived' => Status::ARCHIVED,
                ]),
            ImageField::new('imageForIndex', 'Featured image')
                ->setBasePath($this->kmsUploader->getMediaBaseUrl())
                ->formatValue(fn ($value, Page $entity) => $this->buildFeaturedImageUrl(
                    $this->kmsMediaFinalUrlBuilder,
                    $entity->getFeaturedImage()
                ))
                ->onlyOnIndex(),
            ImageField::new('image', 'Featured image')
                ->setBasePath('')
                ->setUploadDir('/var')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(false)
                ->setFormTypeOption('upload_new', [$this->kmsUploader, 'upload'])
                ->onlyOnForms(),
            AssociationField::new('relateds')
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->andWhere('entity.status = :status')
                        ->setParameter('status', Status::PUBLISHED);
                })
                ->setCrudController(ContentCrudController::class)
                ->autocomplete()
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
        ];
    }
}
