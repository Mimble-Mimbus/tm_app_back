<?php

namespace App\Controller\Admin;

use App\Entity\OpenDay;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class OpenDayCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OpenDay::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('dayStart', 'Ouverture'),
            DateTimeField::new('dayEnd', 'Fermeture'),
        ];
    }
   
}
