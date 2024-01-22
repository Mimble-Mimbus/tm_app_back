<?php

namespace App\Controller\Admin;

use App\Entity\Guild;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewEventGuildCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guild::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            TextEditorField::new('description')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            IntegerField::new('points')
        ];
    }
}
