<?php

namespace App\Controller\Admin;

use App\Entity\Zone;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewEventZoneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Zone::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig')
        ];
    }
   
}
