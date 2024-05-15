<?php

namespace App\Controller\Admin;

use App\Entity\Song;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SongCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Song::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
                     ->remove(Crud::PAGE_INDEX, 'delete')
                     ->remove(Crud::PAGE_INDEX, 'edit')
                     ->remove(Crud::PAGE_DETAIL, 'edit')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield AssociationField::new('artist');
        yield AssociationField::new('album');
        yield AssociationField::new('playlists');
    }
}
