<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArtistCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Artist::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                     ->remove(Crud::PAGE_INDEX, 'delete')
                     ->remove(Crud::PAGE_INDEX, 'edit')
                     ->remove(Crud::PAGE_INDEX, 'new')
                     ->remove(Crud::PAGE_DETAIL, 'edit')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield CollectionField::new('albums')
                             ->renderExpanded()
                             ->setEntryIsComplex()
                             ->useEntryCrudForm()
                             ->setFormTypeOption('label', false)
            ->hideOnIndex();
        yield AssociationField::new('albums')
                               ->onlyOnIndex();
        ;
    }
}
