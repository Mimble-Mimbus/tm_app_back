<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\Quest;
use App\Repository\EventRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestCrudController extends AbstractCrudController
{
    public function __construct(
        private RequestStack $requestStack,
        private EventRepository $eventRepository
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
        return [
            TextField::new('title'),
            TextEditorField::new('infos'),
            AssociationField::new('event', 'Evénement')->hideOnForm(),
            AssociationField::new('zone'),
            IntegerField::new('points'),
            BooleanField::new('isSecret', 'Quête secrète ?')->renderAsSwitch(),
            AssociationField::new('guild', 'Guilde associée'),
            TextField::new('password', 'Mot de passe')
        ];
    }
   
}
