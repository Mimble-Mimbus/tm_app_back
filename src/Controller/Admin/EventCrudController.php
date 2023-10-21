<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\Mapping\Entity;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class EventCrudController extends AbstractCrudController
{
    public function __construct(
        private OrganizationRepository $organizationRepository,
        private RequestStack $requestStack
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $response = $response
                    ->where("entity.organization = :org")
                    ->setParameter('org', $element);
            } elseif ($element instanceof Event) {
                $response = $response
                    ->where("entity.id = :id")
                    ->setParameter('id', $element->getId());
            }
        }

        return $response;
    }

    public function createEntity(string $entityFqcn)
    {
        $event = new Event;
        $organization_param = $this->requestStack->getMainRequest()->query->get('organization');

        if ($organization_param && $organization_param != "") {
            $organization_selected = $this->organizationRepository->find($organization_param);
            $event->setOrganization($organization_selected);
        }

        return $event;
    }

    public function configureFields(string $pageName): iterable
    {
        $organization_param = $this->requestStack->getMainRequest()->query->get('organization');
        $org_field = AssociationField::new('organization', 'Organisation');

        if ($organization_param && $organization_param != "") {
            $organization_selected = $this->organizationRepository->find($organization_param);
            $org_field = AssociationField::new('organization', 'Organisation')->setEmptyData($organization_selected)->setDisabled();
        }
        $fields = [
            $org_field,
            TextField::new('name', 'Nom'),
            TextEditorField::new('presentation', 'Présentation')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            CollectionField::new('urls', 'Urls')->useEntryCrudForm(UrlCrudController::class),
            CollectionField::new('openDays', "Jours d'ouverture")->useEntryCrudForm(OpenDayCrudController::class)
        ];

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Créer un évènement');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('évènements')
            ->setEntityLabelInSingular('évènement')
            ->setPageTitle(Crud::PAGE_INDEX, 'Evènements')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un évènement')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un évènement');
    }
}
