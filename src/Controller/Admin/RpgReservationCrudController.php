<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgActivity;
use App\Entity\RpgReservation;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\RpgActivityRepository;
use App\Repository\RpgTableRepository;
use App\Repository\RpgZoneRepository;
use App\Repository\ZoneRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgReservationCrudController extends AbstractCrudController
{
    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterRpgTable = null;
    private $filterRpgActivity = null;
    
    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private RpgTableRepository $rpgTableRepository,
        private AdminUrlGenerator $adminUrlGenerator,
        private RpgActivityRepository $rpgActivityRepository
    ){
        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $this->filterOrganization = $element;
            } elseif ($element instanceof Event) {
                $this->filterEvent = $element;
            }
        }

        if ($this->requestStack->getMainRequest()->query->get('event')) {
            $this->filterEvent = $this->requestStack->getMainRequest()->query->get('event');
        }

        if ($this->requestStack->getMainRequest()->query->get('organization')) {
            $this->filterOrganization = $this->requestStack->getMainRequest()->query->get('organization');
        }

        if ($this->filterEvent != null) {
            $this->filterEvent = $this->eventRepository->find($this->filterEvent);
        }

        if ($this->requestStack->getMainRequest()->query->get('rpgactivity')) {
            $this->filterRpgTable = $this->rpgActivityRepository->find($this->requestStack->getMainRequest()->query->get('rpgactivity'));
        }

        if ($this->requestStack->getMainRequest()->query->get('rpgtable')) {
            $this->filterRpgTable = $this->rpgTableRepository->find($this->requestStack->getMainRequest()->query->get('rpgtable'));
        }
    }
    
    public static function getEntityFqcn(): string
    {
        return RpgReservation::class;
    }

    public function createEntity($entityFqcn) {
        $entity = new RpgReservation;
        if ($this->filterRpgTable != null) {
            $entity->setRpgTable($this->filterRpgTable);
        }
        return $entity;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters) : QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterRpgTable != null) {
            $response = $response
                ->where("entity.rpgTable = :rpgtable")
                ->setParameter('rpgtable', $this->filterRpgTable);
        } elseif ($this->filterEvent != null) {
            $response = $response
                ->join(Zone::class, 'z', 'WITH', 'entity.zone = z')
                ->where("z.event = :event")
                ->setParameter('event', $this->filterEvent);
        } elseif ($this->filterOrganization != null) {
            $response = $response
                ->join(Zone::class, 'z', 'WITH', 'entity.zone = z')
                ->join(Event::class, 'e', 'WITH', 'z.event = e')
                ->where("e.organization = :org")
                ->setParameter('org', $this->filterOrganization);
        }

        return $response;

    }

    
    public function configureFields(string $pageName): iterable
    {
        $table = AssociationField::new('rpgTable');

        if ($this->filterRpgTable) {
            $table = AssociationField::new('rpgTable', 'Créneau concerné')->setEmptyData($this->filterRpgTable)->setDisabled();

        } else if ($this->filterRpgActivity) {
            $table = AssociationField::new('rpgTable')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('entity.rpgActivity = :r')
                    ->setParameter('r', $this->filterRpgActivity);
            });
        }
        
        return [
            $table,
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('name', 'Nom'),
            EmailField::new('email'),
            TelephoneField::new('phoneNumber', 'Téléphone'),
            IntegerField::new('bookings', 'Nombre de réservations')
        ];
    }

    public function configureCrud(Crud $crud): Crud {
        if ($this->filterRpgActivity) {
            $crud
            ->setPageTitle('index', 'Réservations pour ' . $this->filterRpgActivity->getName())
            ->setPageTitle('new', 'Ajouter une réservations pour ' . $this->filterRpgActivity->getName());

        } else if ($this->filterRpgTable) {
            $crud
            ->setPageTitle('index', 'Réservations pour ' . $this->filterRpgTable->getRpgActivity()->getName())
            ->setPageTitle('new', 'Ajouter une réservations pour ' . $this->filterRpgTable->getRpgActivity()->getName());

        } else {

            $crud
            ->setPageTitle('index', 'Réservations')
            ->setPageTitle('new', 'Ajouter une réservation');
        }
        return $crud;
    }

    public function configureActions(Actions $actions) : Actions {
        return $actions
        ->update(
            Crud::PAGE_INDEX, 
            Action::NEW, 
            function (Action $action) {
                $action->setLabel('Ajouter une réservation');
                if ($this->filterRpgTable != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgReservationCrudController::class)
                                ->setAction('new')
                                ->set('rpgtable', $this->filterRpgTable->getId())
                                ->generateUrl()
                        );
                } else if ($this->filterRpgActivity != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgReservationCrudController::class)
                                ->setAction('new')
                                ->set('rpgactivity', $this->filterRpgActivity->getId())
                                ->generateUrl()
                        );
                } else if ($this->filterEvent != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgReservationCrudController::class)
                                ->setAction('new')
                                ->set('event', $this->filterEvent->getId())
                                ->generateUrl()
                        );
                } elseif ($this->filterOrganization != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgReservationCrudController::class)
                                ->setAction('new')
                                ->set('organization', $this->filterOrganization)
                                ->generateUrl()
                        );
                }
                return $action;
        });
    }
   
}
