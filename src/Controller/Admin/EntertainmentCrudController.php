<?php

namespace App\Controller\Admin;

use App\Entity\Entertainment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EntertainmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Entertainment::class;
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
