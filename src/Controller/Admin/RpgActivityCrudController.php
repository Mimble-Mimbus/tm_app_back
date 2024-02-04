<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgActivity;
use App\Entity\RpgZone;
use App\Entity\Zone;
use App\Repository\EventRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgActivityCrudController extends AbstractCrudController
{
    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterZone = null;
    private $filterRpgZone = null;


    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private ZoneRepository $zoneRepository,
        private RpgZoneRepository $rpgZoneRepository,
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

        if ($this->requestStack->getMainRequest()->query->get('rpgzone')) {
            $this->filterRpgZone = $this->rpgZoneRepository->find($this->requestStack->getMainRequest()->query->get('rpgzone'));
        }
    }
    public static function getEntityFqcn(): string
    {
        return RpgActivity::class;
    }

    public function createEntity($entityFqcn) {
        $entity = new RpgActivity;
        if ($this->filterRpgZone != null) {
            $entity->setRpgZone($this->filterRpgZone);
        }
        return $entity;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterRpgZone != null) {
            $response = $response
                ->where("entity.rpgZone = :rpgzone")
                ->setParameter('rpgzone', $this->filterRpgZone);
        } elseif ($this->filterZone != null) {
            $response = $response
                ->join(RpgZone::class, 'rz', 'WITH', 'entity.rpgZone = rz')
                ->where("rz.zone = :zone")
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
        $rpgTables = CollectionField::new('getActivitySchedules', 'Tables')->setTemplatePath('bundles/easyadmin/fields/collection_rpgTableList.html.twig');
        $rpgzone = AssociationField::new('rpgZone', 'Zone de JDR');
        if ($pageName == 'new') {
            if ($this->filterRpgZone) {
                $rpgzone = $rpgzone->setEmptyData($this->filterRpgZone)->setDisabled(true);
            }
        }
        $fields = [
            $rpgzone,
            AssociationField::new('userGm', 'MJ'),
            AssociationField::new('rpg', 'JDR')->setCrudController(RpgCrudController::class),
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            TextEditorField::new('description')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            CollectionField::new('tags')->useEntryCrudForm(TagCrudController::class),
            CollectionField::new('triggerWarnings')->useEntryCrudForm(TriggerWarningCrudController::class),
            IntegerField::new('maxNumberSeats', 'Nombre de places max'),
            NumberField::new('duration', 'Durée'),
            BooleanField::new('onReservation', 'Sur réservation')->renderAsSwitch(),
            BooleanField::new('isCanceled', 'Annulé')->renderAsSwitch(),
        ];

        if ($pageName == 'index') {
            $fields[] = $rpgTables;
        }

        return $fields;
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
        ->setPageTitle('index', 'Scénarios de JDR proposés')
        ->setPageTitle('new', 'Nouveau scénario de JDR');
    }

    public function configureActions(Actions $actions) : Actions
    {
        $manageRpgTables = Action::new('manageRpgTables', 'Gérer les tables de JDR')
        ->linkToUrl(
            function (RpgActivity $rpgActivity){
                return $this->adminUrlGenerator
                    ->setController(RpgTableCrudController::class)
                    ->setAction('index')
                    ->set('rpgactivity', $rpgActivity->getId())
                    ->unset('rpgzone')
                    ->unset('entityId')
                    ->generateUrl();
            }
        );

        return $actions
        ->add(Crud::PAGE_INDEX, $manageRpgTables)
        ->add(Crud::PAGE_DETAIL, $manageRpgTables)
        ->update(
            Crud::PAGE_INDEX, 
            Action::NEW, 
            function (Action $action) {
                $action->setLabel('Ajouter un JDR');
                if ($this->filterRpgZone != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgActivityCrudController::class)
                                ->setAction('new')
                                ->set('rpgzone', $this->filterRpgZone->getId())
                                ->generateUrl()
                        );
                } elseif ($this->filterZone != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgActivityCrudController::class)
                                ->setAction('new')
                                ->set('zone', $this->filterZone->getId())
                                ->generateUrl()
                        );
                } else if ($this->filterEvent != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgActivityCrudController::class)
                                ->setAction('new')
                                ->set('event', $this->filterEvent->getId())
                                ->generateUrl()
                        );
                } elseif ($this->filterOrganization != null) {
                    return $action
                        ->linkToUrl(
                            $this->adminUrlGenerator
                                ->setController(RpgActivityCrudController::class)
                                ->setAction('new')
                                ->set('organization', $this->filterOrganization)
                                ->generateUrl()
                        );
                }
                return $action;
        });
    }
}
