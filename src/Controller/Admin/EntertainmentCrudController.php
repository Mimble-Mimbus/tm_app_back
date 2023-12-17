<?php

namespace App\Controller\Admin;

use App\Entity\Entertainment;
use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\ZoneRepository;
use Doctrine\DBAL\Query\QueryBuilder as QueryQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class EntertainmentCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterZone = null;

    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private ZoneRepository $zoneRepository,
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

        if ($this->requestStack->getMainRequest()->query->get('organization')) {
            $this->filterOrganization = $this->requestStack->getMainRequest()->query->get('organization');
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
        return Entertainment::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterZone != null) {
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

    public function createEntity($entityFqcn) {
        $entity = new Entertainment;
        if ($this->filterZone != null) {
            $entity->setZone($this->filterZone);
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        
        if ($this->filterZone != null) {
            $zoneField = AssociationField::new('zone')->setEmptyData($this->filterZone)->setDisabled();

        } elseif ($this->filterEvent != null) {
            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('entity.event = :event')
                    ->setParameter('event', $this->filterEvent);
            });

        } elseif ($this->filterOrganization != null) {
            $zoneField = AssociationField::new('zone')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->join(Event::class, 'e', 'WITH', 'e.id = entity.event')
                    ->andWhere('e.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
            });
        } else {
            $zoneField = AssociationField::new('zone');
        }
        
        return [
            $zoneField,
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            TextEditorField::new('description'),
            AssociationField::new('entertainmentType', "Type d'animation"),
            IntegerField::new('maxNumberSeats', 'Nombre de places'),
            NumberField::new('duration', 'Durée')->setHelp('(en minutes)'),
            BooleanField::new('onReservation', 'Sur réservation')->renderAsSwitch(),
            BooleanField::new('isCanceled', 'Annulé')->renderAsSwitch()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $manageSchedules = Action::new('manageSchedules', 'Gérer la programmation')
        ->linkToUrl(
            function (Entertainment $entertainment) {
                return $this->adminUrlGenerator
                    ->setController(EntertainmentScheduleCrudController::class)
                    ->setAction('index')
                    ->set('entertainment', $entertainment->getId())
                    ->unset('entityId')
                    ->generateUrl();
            }
        );
        return $actions
        ->add(Crud::PAGE_INDEX, $manageSchedules)
        ->add(Crud::PAGE_DETAIL, $manageSchedules)
        ->update(
            Crud::PAGE_INDEX, 
            Action::NEW, 
            function (Action $action) {
                $action->setLabel('Créer une animation');
                if ($this->filterZone != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(EntertainmentCrudController::class)
                                ->setAction('new')
                                ->set('zone', $this->filterZone->getId())
                                ->generateUrl()
                        );
                } else if ($this->filterEvent != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(EntertainmentCrudController::class)
                                ->setAction('new')
                                ->set('event', $this->filterEvent->getId())
                                ->generateUrl()
                        );
                } elseif ($this->filterOrganization != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(EntertainmentCrudController::class)
                                ->setAction('new')
                                ->set('organization', $this->filterOrganization)
                                ->generateUrl()
                        );
                }
                return $action;
        });
        
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
        ->setPageTitle('index', 'Animations')
        ->setPageTitle('new', 'Nouvelle animation');
    }
}
