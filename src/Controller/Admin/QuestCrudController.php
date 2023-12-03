<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Quest;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\ZoneRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;
    private $filterZone = null;

    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator,
        private ZoneRepository $zoneRepository
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
        return Quest::class;
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

    public function createEntity(string $entityFqcn)
    {
        $entity = new Quest;

        if ($this->filterZone != null) {
            $entity->setZone($this->filterZone);
            $entity->setEvent($this->filterZone->getEvent());
        }

        if($this->filterEvent != null && $entity->getEvent() == null) {
            $entity->setEvent($this->filterEvent);
        }

        return $entity;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $eventField = AssociationField::new('event', 'Evènement');
        $zoneField = AssociationField::new('zone');

        if ($this->filterZone) {

            $zoneField = AssociationField::new('zone')->setEmptyData($this->filterZone)->setDisabled();
            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterZone->getEvent())->setDisabled();
        
        } elseif ($this->filterEvent) {

            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterEvent)->setDisabled();
        
        } elseif ($this->filterOrganization) {
                
                $eventField = AssociationField::new('event', 'Evènement')->setQueryBuilder(function ($queryBuilder) {
                    $queryBuilder
                    ->andWhere('entity.event.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
                });
        }

        $fields = [];

        switch ($pageName) {
            case 'new':
            case 'edit':
            case 'detail':
                $fields = [
                    $eventField,
                    $zoneField,
                    TextField::new('title', 'Titre'),
                    TextEditorField::new('infos'),
                    IntegerField::new('points'),
                    BooleanField::new('isSecret', 'Quête secrète ?')->renderAsSwitch(),
                    AssociationField::new('guild', 'Guilde associée'),
                    TextField::new('password', 'Mot de passe')
                ];
                break;
            case 'index':
                $fields = [
                    TextField::new('title', 'Titre')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
                    $eventField,
                    $zoneField,
                    TextEditorField::new('infos'),
                    IntegerField::new('points'),
                    BooleanField::new('isSecret', 'Quête secrète ?')->renderAsSwitch(),
                    AssociationField::new('guild', 'Guilde associée'),
                    TextField::new('password', 'Mot de passe')
                ];
                break;
        }
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            $action->setLabel('Créer une quête');
            if ($this->filterZone) {
                return $action
                ->linkToUrl(
                    $this->adminUrlGenerator
                    ->setController(QuestCrudController::class)
                    ->setAction('new')
                    ->set('zone', $this->filterZone->getId())
                    ->generateUrl()
                );
            } elseif ($this->filterEvent) {
                return $action
                ->linkToUrl(
                    $this->adminUrlGenerator
                    ->setController(QuestCrudController::class)
                    ->setAction('new')
                    ->set('event', $this->filterEvent->getId())
                    ->generateUrl()
                );
            } else {
                return $action;
            }

        });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Quêtes')
        ->setEntityLabelInSingular('quête')
        ->setPageTitle('index', 'Quêtes')
        ->setPageTitle('new', 'Créer une quête');
    }
   
}
