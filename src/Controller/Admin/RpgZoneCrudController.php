<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgZone;
use App\Repository\EventRepository;
use App\Repository\ZoneRepository;
use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgZoneCrudController extends AbstractCrudController
{
    public function __construct(
        private ZoneRepository $zoneRepository,
        private RequestStack $requestStack,
        private EventRepository $eventRepository
    )
    {        
    }

    public static function getEntityFqcn(): string
    {
        return RpgZone::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): ORMQueryBuilder
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
        $entity = new RpgZone;

        if ($this->requestStack->getMainRequest()->query->get('event')) {
            $entity->setEvent($this->eventRepository->find($this->requestStack->getMainRequest()->query->get('event')));
        }

        return $entity;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')->setColumns('col-12'),
            AssociationField::new('event', 'Evénement')->hideOnForm(),
            AssociationField::new('zone'),
            IntegerField::new('availableTables', 'Nombre de tables disponibles')->setColumns('col-12'),
            IntegerField::new('maxAvailableSeatsPerTable', 'Nombre de places par table')->setColumns('col-12'),
            TextField::new('minStartHour', 'Heure minimale de début de partie')->setHelp('Exemples : 9h00, 14h30')->setColumns('col-12'),
            TextField::new('maxEndHour', 'Heure maximale de fin de partie')->setHelp('Exemples : 9h00, 14h30')->setColumns('col-12'),
        ];
    }
}
