<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgZone;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\ZoneRepository;
use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgZoneCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;

    private $filterZone = null;

    public function __construct(
        private ZoneRepository $zoneRepository,
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
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

        if ($this->filterEvent != null) {
            $this->filterEvent = $this->eventRepository->find($this->filterEvent);
        }

        if ($this->requestStack->getMainRequest()->query->get('zone')) {
            $this->filterZone = $this->zoneRepository->find($this->requestStack->getMainRequest()->query->get('zone'));
        }
    }

    public static function getEntityFqcn(): string
    {
        return RpgZone::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): ORMQueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterZone != null) {
            $response = $response->where('entity.zone = :zone')->setParameter(':zone', $this->filterZone);
        } elseif ($this->filterEvent != null) {
            $response = $response
                ->where('entity.event = :event')
                ->setParameter('event', $this->filterEvent);
        } 
        if ($this->filterOrganization != null) {
            $response = $response
                ->join(Zone::class, 'z', 'WITH', 'entity.zone = z')
                ->join(Event::class, 'e', 'WITH', 'z.event = e')
                ->where("e.organization = :org")
                ->setParameter('org', $this->filterOrganization);
        }

        return $response;
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = new RpgZone;

        if ($this->filterZone != null) {
            $entity->setZone($this->filterZone)
                ->setEvent($this->filterZone->getEvent());
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        $zoneField = AssociationField::new('zone');

        if ($this->filterZone != null) {

            $zoneField = AssociationField::new('zone')->setEmptyData($this->filterZone)->setDisabled();

        } elseif ($this->filterOrganization != null) {


            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->join(Event::class, 'e', 'WITH', 'entity.event = e')
                    ->andWhere('event.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
            });
        } elseif ($this->filterEvent != null) {

            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('entity.event = :event')
                    ->setParameter('event', $this->filterEvent);
            });
        }



        return [
            TextField::new('name', 'Nom')->setColumns('col-12')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            $zoneField,
            IntegerField::new('availableTables', 'Nombre de tables disponibles')->setColumns('col-12'),
            IntegerField::new('maxAvailableSeatsPerTable', 'Nombre de places par table')->setColumns('col-12'),
            CollectionField::new('rpgActivities')->hideOnForm(),
            TextField::new('minStartHour', 'Heure minimale de début de partie')->hideOnIndex()->setHelp('Exemples: 9h00, 14h30'),
            TextField::new('maxEndHour', 'Heure maximale de fin de partie')->hideOnIndex()->setHelp('Exemples : 9h00, 14h30'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setEvent($entityInstance->getZone()->getEvent());
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->setEvent($entityInstance->getZone()->getEvent());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $zone = $entityInstance->getZone();
        $zone->setRpgZone(null);
        parent::deleteEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions) : Actions
    {
        $manageRpgActivities = Action::new('manageRpgActivities', 'Gérer les tables de JDR')
        ->linkToUrl(
            function (RpgZone $rpgZone) {
                return $this->adminUrlGenerator
                    ->setController(RpgActivityCrudController::class)
                    ->setAction('index')
                    ->set('rpgzone', $rpgZone->getId())
                    ->unset('entityId')
                    ->generateUrl();
            }
        );

        return $actions
        ->add(Crud::PAGE_INDEX, $manageRpgActivities)
        ->add(Crud::PAGE_DETAIL, $manageRpgActivities);
    }
}
