<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\VolunteerShift;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserTMRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class VolunteerShiftCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterZone = null;
    private $filterVolunteer = null;
    private $calendar_view = false;

    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private ZoneRepository $zoneRepository,
        private UserTMRepository $userTMRepository,
        private OrganizationRepository $organizationRepository,
        private AdminUrlGenerator $adminUrlGenerator
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

        if ($this->requestStack->getMainRequest()->query->get('zone')) {
            $this->filterZone = $this->zoneRepository->find($this->requestStack->getMainRequest()->query->get('zone'));
        }

        if ($this->requestStack->getMainRequest()->query->get('volunteer')) {
            $this->filterVolunteer = $this->userTMRepository->find($this->requestStack->getMainRequest()->query->get('volunteer'));
        }

        if ($this->requestStack->getMainRequest()->query->get('view') && $this->requestStack->getMainRequest()->query->get('view') == 'calendar') {
            $this->calendar_view = true;
        }
    }

    public static function getEntityFqcn(): string
    {
        return VolunteerShift::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterVolunteer != null) {
            $response = $response
                ->where("entity.user = :user")
                ->setParameter('user', $this->filterVolunteer);
        } elseif ($this->filterZone != null) {
            $response = $response
                ->where("entity.zone = :zone")
                ->setParameter('zone', $this->filterZone);
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
        $eventField = AssociationField::new('event', 'Evénement');
        $zoneField = AssociationField::new('zone');

        if ($this->filterOrganization) {
            $eventField = AssociationField::new('event', 'Evénement')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('e.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
            });

            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->join(Event::class, 'e', 'WITH', 'e.id = entity.event')
                    ->andWhere('e.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
            });
        }
        if( $this->filterEvent != null) {
            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterEvent)->setDisabled();

            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('entity.event = :event')
                    ->setParameter('event', $this->filterEvent);
            });
        }

        if( $this->filterZone != null) {
            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterZone->getEvent())->setDisabled();
            $eventField = AssociationField::new('zone')->setEmptyData($this->filterZone)->setDisabled();
        }

        return [
            AssociationField::new('user', 'Bénévole'),
            $eventField,
            $zoneField,
            DateTimeField::new('shiftStart', 'Prise de poste'),
            DateTimeField::new('shiftEnd', 'Fin de période'),
            TextEditorField::new('description'),
        ];
    }

    public function configureCrud(Crud $crud) : Crud
    {
        if ($this->calendar_view) {

            $crud
            ->overrideTemplates([
                'crud/index' => 'bundles/easyadmin/volunteer_plannings/index.html.twig'
            ]);
        }
        
        return $crud;
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        $events = $this->eventRepository->findAll();
        $zones = $this->zoneRepository->findAll();
        $volunteers = $this->userTMRepository->getVolunteers();

        if (Crud::PAGE_INDEX === $responseParameters->get('pageName') && $this->calendar_view) {

            $responseParameters->set('events', $events);
            $responseParameters->set('zones', $zones);
            $responseParameters->set('volunteers', $volunteers);

        }

        return $responseParameters;
    }

    public function configureActions(Actions $actions): Actions
    {
        $displayVonlunteerCalendar = Action::new('displayVonlunteerCalendar', 'Voir le planning de ce bénévole')
        ->linkToUrl(
            function (VolunteerShift $volunteerShift) {
                return $this->adminUrlGenerator
                    ->setController(VolunteerShiftCrudController::class)
                    ->setAction('index')
                    ->set('volunteer', $volunteerShift->getUser()->getId())
                    ->set('view', 'calendar')
                    ->unset('entityId')
                    ->generateUrl();
            }
        );

        $displayGlobalCalendar = Action::new('displayGlobalCalendar', 'Voir le planning général')
        ->linkToUrl(
            function () {
                return $this->adminUrlGenerator
                    ->setController(VolunteerShiftCrudController::class)
                    ->setAction('index')
                    ->set('view', 'calendar')
                    ->unset('entityId')
                    ->generateUrl();
            }
        )
        ->createAsGlobalAction();

        return $actions
        ->add(Crud::PAGE_INDEX, $displayVonlunteerCalendar)
        ->add(Crud::PAGE_INDEX, $displayGlobalCalendar)

        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action){
            return $action->setLabel('Ajouter une période au calendrier');
        })
        ->add(Crud::PAGE_DETAIL, $displayVonlunteerCalendar);

    }
    
}
