<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgActivity;
use App\Entity\RpgTable;
use App\Entity\RpgZone;
use App\Repository\EventRepository;
use App\Repository\RpgActivityRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgTableCrudController extends AbstractCrudController
{
    private $filterEvent = null;
    private $filterOrganization = null;

    private $filterActivity = null;

    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator,
        private RpgActivityRepository $rpgActivityRepository
    ) {
        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $this->filterOrganization = $element;
            } elseif ($element instanceof Event) {
                $this->filterEvent = $element;
            }
        }

        if ($this->filterEvent != null) {
            $this->filterEvent = $this->eventRepository->find($this->filterEvent);
        }

        if ($this->requestStack->getMainRequest()->query->get('rpgactivity')) {
            $this->filterActivity = $this->rpgActivityRepository->find($this->requestStack->getMainRequest()->query->get('rpgactivity'));
        }
    }

    
    public static function getEntityFqcn(): string
    {
        return RpgTable::class;
    }

    public function createEntity($entityFqcn) {
        $entity = new RpgTable;
        $entity->setIsCanceled(false);
        if ($this->filterActivity != null) {
            $entity->setRpgActivity($this->filterActivity);
        }
        return $entity;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterActivity != null) {
            $response = $response
                ->where("entity.rpgActivity = :rpgActivity")
                ->setParameter('rpgActivity', $this->filterActivity);
        } elseif ($this->filterEvent != null) {
            $response = $response
                ->join(RpgZone::class, 'z', 'WITH', 'entity.rpgZone = z')
                ->where("z.event = :event")
                ->setParameter('event', $this->filterEvent);
        } elseif ($this->filterOrganization != null) {
            $response = $response
                ->join(RpgZone::class, 'z', 'WITH', 'entity.rpgZone = z')
                ->join(Event::class, 'e', 'WITH', 'z.event = e')
                ->where("e.organization = :org")
                ->setParameter('org', $this->filterOrganization);
        }

        return $response;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            DateTimeField::new ('start', 'Début'),
            NumberField::new('duration', 'Durée (en heures)')
        ];

        if ($pageName != 'new') {
            $fields[] = CollectionField::new('activityReservations', 'Réservations');
            $fields[] = BooleanField::new('isCanceled', 'Annulé')->renderAsSwitch();
        }

        return $fields;
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
        ->setPageTitle('index', 'Tables proposées')
        ->setPageTitle('new', 'Nouvelle table');
    }

    public function configureActions(Actions $actions) : Actions
    {
        $manageReservations = Action::new('manageReservations', 'Gérer les réservations')
        ->linkToUrl(
            function (RpgTable $rpgTable){
                return $this->adminUrlGenerator
                    ->setController(RpgReservationCrudController::class)
                    ->setAction('index')
                    ->set('rpgtable', $rpgTable->getId())
                    ->unset('rpgactivity')
                    ->unset('entityId')
                    ->generateUrl();
            }
        );

        return $actions
        ->add(Crud::PAGE_INDEX, $manageReservations)
        ->add(Crud::PAGE_DETAIL, $manageReservations)
        ->update(
            Crud::PAGE_INDEX, 
            Action::NEW, 
            function (Action $action) {
                $action->setLabel('Ajouter une session');
                if ($this->filterActivity != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgTableCrudController::class)
                                ->setAction('new')
                                ->set('rpgactivity', $this->filterActivity->getId())
                                ->generateUrl()
                        );
                } else if ($this->filterEvent != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgTableCrudController::class)
                                ->setAction('new')
                                ->set('event', $this->filterEvent->getId())
                                ->generateUrl()
                        );
                } elseif ($this->filterOrganization != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgTableCrudController::class)
                                ->setAction('new')
                                ->set('organization', $this->filterOrganization)
                                ->generateUrl()
                        );
                }
                return $action;
        });
    }
   
}
