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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
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
        return [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            TextEditorField::new('description'),
            TextField::new('rpgZone.zone.event', 'Evènement'),
            CollectionField::new('rpgTables', 'Tables')
        ];
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
        ->setPageTitle('index', 'JDR proposés')
        ->setPageTitle('new', 'Nouveau JDR');
    }

    public function configureActions(Actions $actions) : Actions
    {
        return $actions
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
