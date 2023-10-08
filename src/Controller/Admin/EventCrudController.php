<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('presentation', 'Présentation')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            AssociationField::new('organization', 'Organisation'),
            CollectionField::new('urls', 'Urls')->useEntryCrudForm(UrlCrudController::class),
            CollectionField::new('openDays', "Jours d'ouverture")->useEntryCrudForm(OpenDayCrudController::class)
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            return $action
            ->setLabel('Créer un évènement');
        })
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('évènements')
        ->setEntityLabelInSingular('évènement')
        ->setPageTitle(Crud::PAGE_INDEX, 'Evènements')
        ->setPageTitle(Crud::PAGE_NEW, 'Créer un évènement')
        ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un évènement')
        ;
    }
}
