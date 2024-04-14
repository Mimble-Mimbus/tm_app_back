<?php

namespace App\Controller\Admin;

use App\Entity\EntertainmentReservation;
use App\Entity\UserTM;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserTMCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserTM::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $role_field = ArrayField::new('roles');
        if ($pageName == 'edit' or $pageName == 'new') {
            $role_field = ChoiceField::new('roles')
                ->setChoices(
                    ['Bénévole' => 'ROLE_VOLUNTEER', 
                    'Administrateurice' => 'ROLE_ADMIN', 
                    'Visiteureuse' => 'ROLE_VISITOR'
                    ])
                ->allowMultipleChoices(true);
        }
        $fields = [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            EmailField::new('email'),
            TextField::new('telephone', 'Téléphone'),
            $role_field,         
        ];

        if ($pageName == 'detail') {
            $fields = [
                FormField::addTab('Identité'),
                TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
                EmailField::new('email'),
                TextField::new('telephone', 'Téléphone'),
                $role_field,
                FormField::addTab('Réservations'),
                ArrayField::new('getReservationsList', "Animations réservées"),
                ArrayField::new('getRpgReservationsList', "JDR réservés"),
                ArrayField::new('getRpgActivitiesList', "JDR proposés"),
                FormField::addTab('Guilde favorite'),
                TextField::new('guild', 'Guilde')
            ];
            $instance = $this->getContext()->getEntity()->getInstance();
            if (in_array('ROLE_VOLUNTEER', $instance->getRoles())) {
                $fields[] = FormField::addTab('Shifts');
                $fields[] =CollectionField::new('volunteerShifts', 'Shifts')->setTemplatePath('bundles/easyadmin/fields/user_shifts.html.twig');
            }
        }
        return $fields;
    }

    public function configureActions(Actions $actions) : Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            return $action->setLabel('Ajouter un compte utilisateur');
        });
    }
   
}
