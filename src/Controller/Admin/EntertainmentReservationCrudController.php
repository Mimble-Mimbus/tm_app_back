<?php

namespace App\Controller\Admin;

use App\Entity\EntertainmentReservation;
use App\Repository\EntertainmentScheduleRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\RequestStack;

class EntertainmentReservationCrudController extends AbstractCrudController
{

    private $schedule = null;

    public function __construct(
        private EntertainmentScheduleRepository $entertainmentScheduleRepository,
        private RequestStack $requestStack,
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
        $this->schedule = $this->requestStack->getMainRequest()->query->get('schedule');
        if ($this->schedule != null) {
            $this->schedule = $this->entertainmentScheduleRepository->find($this->schedule);
        }

    }

    public static function getEntityFqcn(): string
    {
        return EntertainmentReservation::class;
    }

    public function createEntity($entityFqcn)
    {
        $entity = new EntertainmentReservation;

        if ($this->schedule != null) {
            $entity->setEntertainmentSchedule($this->schedule);
        }
        return $entity;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters) : QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->schedule != null) {
            $response = $response
                ->where("entity.entertainmentSchedule = :ent")
                ->setParameter('ent', $this->schedule->getId());
        }

        return $response;

    }
    
    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        $fields = [
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('name', 'Nom'),
            TextField::new('email'),
            TextField::new('phoneNumber', 'Téléphone'),
            IntegerField::new('bookings', 'Nombre de places'),
            AssociationField::new('entertainmentSchedule', 'Créneau de réservation')->setEmptyData($this->schedule)->setDisabled(),
        ];

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            return $action
            ->setLabel('Ajouter une réservation')
            ->linkToUrl(
                $this->adminUrlGenerator
                ->setAction('new')
                ->setController(EntertainmentReservationCrudController::class)
                ->set('schedule', $this->schedule->getId())
                ->generateUrl()
            );
        });
        
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
        ->setPageTitle('index', 'Réservations pour ' . $this->schedule->getEntertainment() . ', ' . $this->schedule)
        ->setPageTitle('new', 'Nouvelle réservations pour ' . $this->schedule->getEntertainment())
        ;
    }
   
}
