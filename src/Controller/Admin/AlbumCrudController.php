<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AlbumCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Album::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                     ->remove(Crud::PAGE_INDEX, 'delete')
                     ->remove(Crud::PAGE_INDEX, 'edit')
                     ->remove(Crud::PAGE_DETAIL, 'edit')
                     ->remove(Crud::PAGE_INDEX, 'new')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield AssociationField::new('artist');

        yield CollectionField::new('songs')
                             ->renderExpanded()
                             ->setEntryIsComplex()
                             ->useEntryCrudForm()
                             ->hideOnIndex()
        ;
        yield AssociationField::new('songs')
                              ->onlyOnIndex()
        ;
    }
}
