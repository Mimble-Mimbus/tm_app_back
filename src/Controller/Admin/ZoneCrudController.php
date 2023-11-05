<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Zone;
use App\Repository\EventRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class ZoneCrudController extends AbstractCrudController
{
    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator
    ) {

    }
    public static function getEntityFqcn(): string
    {
        return Zone::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $response = $response
                    ->join(Event::class, 'e', 'WITH', 'entity.event = e')
                    ->where("e.organization = :org")
                    ->setParameter('org', $element);
            } elseif ($element instanceof Event) {
                $response = $response
                    ->where("entity.event = :event")
                    ->setParameter('event', $element);
            }
        }

        if ($this->requestStack->getMainRequest()->query->get('event')) {
            $event = $this->requestStack->getMainRequest()->query->get('event');

            $response = $response
                ->where("entity.event = :event")
                ->setParameter('event', $event);
        }

        return $response;
        
    }
    public function configureFields(string $pageName): iterable
    {
        $eventField = AssociationField::new('event', 'Evènement');

        if($this->requestStack->getMainRequest()->query->get('event')) {

            $selectedEvent = $this->eventRepository->find($this->requestStack->getMainRequest()->query->get('event'));
            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($selectedEvent)->setDisabled();
        
        } elseif ($this->requestStack->getMainRequest()->getSession()->get('filterByElement') 
        && $this->requestStack->getMainRequest()->getSession()->get('filterByElement') instanceof Organization) {

            $eventField = AssociationField::new('event', 'Evènement')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                ->andWhere('entity.organization = :org')
                ->setParameter('org', $this->requestStack->getMainRequest()->getSession()->get('filterByElement'));
            });
        }
        return [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            $eventField,
            AssociationField::new('rpgZone')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $newRpgZone = Action::new('newRpgZone', 'Ajouter une zone de JDR')
        ->displayIf(function (Zone $zone) {
            return $zone->getRpgZone() == null;
        })
        ->linkToUrl( function (Zone $zone) {
            return $this->adminUrlGenerator
            ->setController(RpgZoneCrudController::class)
            ->setAction('new')
            ->set('zone', $zone->getId())
            ->unset('entityId')
            ->generateUrl();
        }
    );

        return $actions
        ->add(Crud::PAGE_INDEX, $newRpgZone)
        ->add(Crud::PAGE_DETAIL, $newRpgZone)
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            $action->setLabel('Créer une zone');
            $selected_event = null;

            if ($this->requestStack->getSession()->get('filterByElement') && $this->requestStack->getSession()->get('filterByElement') instanceof Event) {
                $selected_event = $this->requestStack->getSession()->get('filterByElement')->getId();
            } elseif ($this->requestStack->getMainRequest()->query->get('event')) {
                $selected_event = $this->requestStack->getMainRequest()->query->get('event');
            }

            return $action
            ->linkToUrl(
                $this->adminUrlGenerator
                ->setController(ZoneCrudController::class)
                ->setAction('new')
                ->set('event', $selected_event)
                ->generateUrl()
            );
        });
        
    }

    public function configureCrud(Crud $crud): Crud 
    {
        return $crud
        ->setEntityLabelInPlural('Zones')
        ->setEntityLabelInSingular('zone')
        ->setPageTitle('new', 'Créer une zone')
        ;
    }
   
}
