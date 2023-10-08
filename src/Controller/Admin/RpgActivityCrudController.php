<?php

namespace App\Controller\Admin;

use App\Entity\RpgActivity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RpgActivityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RpgActivity::class;
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
