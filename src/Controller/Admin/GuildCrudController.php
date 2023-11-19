<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Guild;
use App\Entity\Organization;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class GuildCrudController extends AbstractCrudController
{

    private $filterEvent = null;
    private $filterOrganization = null;

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
        return Guild::class;
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
        $entity = new Guild;
        $entity->setPoints(0);

        if ($this->filterEvent) {
            $entity->setEvent($this->filterEvent);
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        $eventField = AssociationField::new('event', 'Evènement');

        if ($this->filterEvent != null) {

            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($this->filterEvent)->setDisabled();
        } elseif ($this->filterOrganization != null) {

            $eventField = AssociationField::new('event', 'Evènement')->setQueryBuilder(function ($queryBuilder) {
                $queryBuilder
                    ->andWhere('entity.organization = :org')
                    ->setParameter('org', $this->filterOrganization);
            });
        }

        return [
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            $eventField,
            TextEditorField::new('description')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            IntegerField::new('points')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                $action->setLabel('Créer une guilde');
                
                if ($this->filterEvent != null) {

                    $action
                    ->linkToUrl(
                        $this->adminUrlGenerator
                        ->setController(GuildCrudController::class)
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
            ->setEntityLabelInPlural('Guildes')
            ->setEntityLabelInSingular('guilde')
            ->setPageTitle('index', 'Guildes')
            ->setPageTitle('new', 'Créer une guilde');
    }
}
