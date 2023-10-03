<?php

namespace App\Controller\Admin;

use App\Entity\Paymentable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PaymentableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Paymentable::class;
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
