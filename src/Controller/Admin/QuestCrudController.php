<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Quest;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestCrudController extends AbstractCrudController
{
    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }
    
    public static function getEntityFqcn(): string
    {
        return Quest::class;
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

    public function createEntity(string $entityFqcn)
    {
        $entity = new Quest;

        if ($this->requestStack->getMainRequest()->query->get('event')) {
            $entity->setEvent($this->eventRepository->find($this->requestStack->getMainRequest()->query->get('event')));
        }

        return $entity;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $eventField = AssociationField::new('event', 'Evènement');

        if($this->requestStack->getMainRequest()->query->get('event')) {

            $selectedEvent = $this->eventRepository->find($this->requestStack->getMainRequest()->query->get('event'));
            $eventField = AssociationField::new('event', 'Evènement')->setEmptyData($selectedEvent)->setDisabled();
        
        } elseif ($this->requestStack->getMainRequest()->getSession()->get('filterByElement')) {

            if ($this->requestStack->getMainRequest()->getSession()->get('filterByElement') instanceof Organization) {
                
                $eventField = AssociationField::new('event', 'Evènement')->setQueryBuilder(function ($queryBuilder) {
                    $queryBuilder
                    ->andWhere('entity.organization = :org')
                    ->setParameter('org', $this->requestStack->getMainRequest()->getSession()->get('filterByElement'));
                });
            } elseif ($this->requestStack->getMainRequest()->getSession()->get('filterByElement') instanceof Event) {
                $selectedEvent = $this->eventRepository->find($this->requestStack->getMainRequest()->query->get('event'));
                AssociationField::new('event', 'Evènement')->setEmptyData($selectedEvent)->setDisabled();
            }
        }

        $fields = [];

        switch ($pageName) {
            case 'new':
            case 'edit':
            case 'detail':
                $fields = [
                    $eventField,
                    AssociationField::new('zone'),
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
                    AssociationField::new('zone'),
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
            $selected_event = null;

            if ($this->requestStack->getMainRequest()->query->get('event')) {
                $selected_event = $this->requestStack->getMainRequest()->query->get('event');
            }elseif ($this->requestStack->getSession()->get('filterByElement') && $this->requestStack->getSession()->get('filterByElement') instanceof Event) {
                $selected_event = $this->requestStack->getSession()->get('filterByElement')->getId();
            }

            return $action
            ->linkToUrl(
                $this->adminUrlGenerator
                ->setController(QuestCrudController::class)
                ->setAction('new')
                ->set('event', $selected_event)
                ->generateUrl()
            );
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
