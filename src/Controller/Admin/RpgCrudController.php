<?php

namespace App\Controller\Admin;

use App\Entity\Rpg;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RpgCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rpg::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            TextField::new('publisher', 'Editeur'),
            TextField::new('universe', 'Univers'),
            CollectionField::new('tags')->useEntryCrudForm(TagCrudController::class),
            CollectionField::new('triggerWarnings')->useEntryCrudForm(TriggerWarningCrudController::class)
        ];
    }
   
}
