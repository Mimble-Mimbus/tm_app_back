<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Paymentable;
use App\Repository\EventRepository;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentableCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private  $filterOrganization = null;

    public function __construct(
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
    }

    public static function getEntityFqcn(): string
    {
        return Paymentable::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->filterEvent != null) {
            $response = $response
                ->where('entity.event = :event')
                ->setParameter('event', $this->filterEvent);
        } elseif ($this->filterOrganization != null) {
            $response = $response->join(Event::class, 'e', 'WITH', 'entity.event = e')
                ->where("e.organization = :org")
                ->setParameter('org', $this->filterOrganization);
        }

        return $response;
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = new Paymentable;

        if ($this->filterEvent != null) {
            $entity->setEvent($this->filterEvent);
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        $eventField = AssociationField::new('event', 'Evènement');

        if($this->filterEvent != null) {

            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterEvent)->setDisabled();
        
        } elseif ($this->filterOrganization != null) {

            $eventField = AssociationField::new('event', 'Evènement')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                ->andWhere('entity.organization = :org')
                ->setParameter('org', $this->filterOrganization);
            });
        }

        if($pageName == 'index') {
            return [
                TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
                $eventField,
                AssociationField::new('typePaymentable', 'Catégorie'),
                CollectionField::new('prices', 'Prix')->setTemplatePath('bundles/easyadmin/fields/collection_pricelist.html.twig')
            ];

        } else {
            return [
                $eventField,
                TextField::new('name', 'Nom'),
                AssociationField::new('typePaymentable', 'Catégorie'),
                CollectionField::new('prices', 'Prix')->useEntryCrudForm(PriceCrudController::class)->setTemplatePath('bundles/easyadmin/fields/collection_pricelist.html.twig')
    
            ];
        }

    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function(Action $action) {
            $action->setLabel('Créer un article facturable');

            if ($this->filterEvent != null) {
                return $action->linkToUrl(
                    $this->adminUrlGenerator
                    ->setController(PaymentableCrudController::class)
                    ->setAction('new')
                    ->set('event', $this->filterEvent)
                    ->generateUrl()
                );
            }
            return $action;
        });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInSingular('Article facturable')
        ->setEntityLabelInPlural('Articles facturables')
        ->setPageTitle('index', 'Articles facturables')
        ->setPageTitle('new', 'Créer un article facturable')
        ;
    }
}
