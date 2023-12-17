<?php

namespace App\Controller\Admin;

use App\Entity\EntertainmentSchedule;
use App\Repository\EntertainmentRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class EntertainmentScheduleCrudController extends AbstractCrudController
{

    private $entertainment = null;

    public function __construct(
        private RequestStack $requestStack,
        private EntertainmentRepository $entertainmentRepository,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->entertainment = $this->requestStack->getMainRequest()->query->get('entertainment');
        if ($this->entertainment != null) {
            $this->entertainment = $this->entertainmentRepository->find($this->entertainment);
        }
    }

    public static function getEntityFqcn(): string
    {
        return EntertainmentSchedule::class;
    }

    public function createEntity($entityFqcn) {
        $entity = new EntertainmentSchedule;
        if ($this->entertainment != null ) {
            $entity->setEntertainment($this->entertainment);
        }
        return $entity;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        
        if ($this->entertainment != null) {
            $response = $response
                ->where("entity.entertainment = :ent")
                ->setParameter('ent', $this->entertainment->getId());
        }

        return $response;
    }

    public function configureActions(Actions $actions) : Actions
    {
        $manageReservations = Action::new('manageReservation', 'Gérer les réservations')
        ->linkToUrl(
            function (EntertainmentSchedule $entertainmentSchedule) {
                return $this->adminUrlGenerator
                    ->setController(EntertainmentReservationCrudController::class)
                    ->setAction('index')
                    ->set('schedule', $entertainmentSchedule->getId())
                    ->unset('entityId')
                    ->generateUrl();
            }
        );
        return $actions
        ->add(Crud::PAGE_INDEX, $manageReservations)
        ->remove(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            return $action
            ->setLabel('Ajouter un créneau')
            ->linkToUrl(
                $this->adminUrlGenerator
                ->setController(EntertainmentScheduleCrudController::class)
                ->setAction('new')
                ->set('entertainment', $this->entertainment->getId())
                ->generateUrl()
            );
        });

    }
    
    public function configureFields(string $pageName): iterable
    {
        
        $fields = [
            AssociationField::new('entertainment', 'Animation')->setEmptyData($this->entertainment)->setDisabled(),
            DateTimeField::new('start', 'Début')->setFormat('dd-MM-yyyy HH:mm'),
            IntegerField::new('duration', 'Durée (en minutes)'),
            BooleanField::new('isCanceled', 'Annulé')
        ];

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
        ->setPageTitle('index', 'Programmation pour ' . $this->entertainment)
        ->setPageTitle('new', 'Ajouter un créneau pour ' . $this->entertainment)
        ->setDefaultSort(['start' => 'ASC']);
    }
    
}
