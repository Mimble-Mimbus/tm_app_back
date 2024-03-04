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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class VolunteerShiftCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterZone = null;
    private $filterVolunteer = null;

    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private ZoneRepository $zoneRepository,
        private UserTMRepository $userTMRepository,
        private OrganizationRepository $organizationRepository
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
        return [
            AssociationField::new('user'),
            AssociationField::new('event'),
            AssociationField::new('zone'),
            DateTimeField::new('shiftStart'),
            DateTimeField::new('shiftEnd'),
            TextEditorField::new('description'),
        ];
    }

    public function configureCrud(Crud $crud) : Crud
    {
        $crud
        ->setSearchFields(null)
        ->overrideTemplates([
            'crud/index' => 'bundles/easyadmin/volunteer_plannings/index.html.twig'
        ]);
        if ($this->filterVolunteer) {
            $crud->setPageTitle('index', 'Planning de ' . $this->filterVolunteer);
        } elseif ($this->filterZone) {
            $crud->setPageTitle('index', 'Planning des bénévoles pour la zone ' . $this->filterZone);
        } elseif ($this->filterEvent) {
            $crud->setPageTitle('index', 'Planning des bénévoles pour ' . $this->filterEvent);
        } elseif ($this->filterOrganization) {
            $crud->setPageTitle('index', 'Planning des bénévoles pour les événements de ' . $this->filterOrganization);
        }
        return $crud;
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        if (Crud::PAGE_INDEX === $responseParameters->get('pageName')) {
            $filter = null;
            $filter_id = null;
            if ($this->filterVolunteer) {
                $filter = 'volunteer';
                $filter_id = $this->filterVolunteer->getId();
            } elseif ($this->filterZone) {
                $filter = 'zone';
                $filter_id = $this->filterZone->getId();
            } elseif ($this->filterEvent) {
                $filter = 'event';
                $filter_id = $this->filterEvent->getId();
            } elseif ($this->filterOrganization) {
                $filter = 'organization';  
                $filter_id = $this->filterOrganization->getId();  
            }
            $responseParameters->set('calendar_filter', $filter);
            $responseParameters->set('calendar_filter_id', $filter_id);

        }

        return $responseParameters;
    }

    
}
