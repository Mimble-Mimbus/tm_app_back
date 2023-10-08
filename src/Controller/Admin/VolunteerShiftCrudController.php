<?php

namespace App\Controller\Admin;

use App\Entity\VolunteerShift;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VolunteerShiftCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VolunteerShift::class;
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
