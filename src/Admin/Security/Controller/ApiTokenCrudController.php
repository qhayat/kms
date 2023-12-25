<?php

namespace Kms\Admin\Security\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Kms\Core\Security\Entity\ApiToken;

class ApiTokenCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApiToken::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('token')
                ->onlyOnForms(),
            AssociationField::new('permissions', 'Permissions')
                ->setFormTypeOption('by_reference', false)
                ->setRequired(false),
            BooleanField::new('enabled'),
        ];
    }
}
