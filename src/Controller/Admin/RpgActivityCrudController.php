<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Entity\RpgActivity;
use App\Entity\RpgZone;
use App\Entity\Zone;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class RpgActivityCrudController extends AbstractCrudController
{

    public function __construct(
        private RequestStack $requestStack
    ){}
    public static function getEntityFqcn(): string
    {
        return RpgActivity::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $response = $response
                    ->join(RpgZone::class, 'rz', 'WITH', 'entity.rpgZone = rz')
                    ->join(Zone::class, 'z', 'WITH', 'rz.zone = z')
                    ->join(Event::class, 'e', 'WITH', 'z.event = e')
                    ->where("e.organization = :org")
                    ->setParameter('org', $element);
            } elseif ($element instanceof Event) {
                $response = $response
                    ->join(RpgZone::class, 'rz', 'WITH', 'entity.rpgZone = rz')
                    ->join(Zone::class, 'z', 'WITH', 'rz.zone = z')
                    ->where("z.event = :event")
                    ->setParameter('event', $element);
            }
        }

        if ($this->requestStack->getMainRequest()->query->get('event')) {
            $event = $this->requestStack->getMainRequest()->query->get('event');

            $response = $response
                ->join(RpgZone::class, 'rz', 'WITH', 'entity.rpgZone = rz')
                ->join(Zone::class, 'z', 'WITH', 'rz.zone = z')
                ->where("z.event = :event")
                ->setParameter('event', $event);
        }

        return $response;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description'),
            TextField::new('rpgZone.zone.event')
        ];
    }
    
}
