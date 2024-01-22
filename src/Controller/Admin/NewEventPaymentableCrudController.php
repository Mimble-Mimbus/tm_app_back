<?php

namespace App\Controller\Admin;

use App\Entity\Paymentable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewEventPaymentableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Paymentable::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            AssociationField::new('typePaymentable', 'Catégorie'),
            CollectionField::new('prices', 'Prix')->useEntryCrudForm(PriceCrudController::class)->setTemplatePath('bundles/easyadmin/fields/collection_pricelist.html.twig')

        ];
    }
    
}
