<?php

namespace App\Controller\Admin;

use App\Entity\PlaylistType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlaylistTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PlaylistType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
