<?php

namespace App\Controller\Admin;

use App\Entity\Price;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Price::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('priceCondition', 'Condition')->setHelp('Exemple : lot de 3, tarif -12 ans')->setColumns(12),
            NumberField::new('price', 'Prix')->setColumns(12),
        ];
    }
   
}
