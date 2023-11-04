<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Organization;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

use function PHPUnit\Framework\isInstanceOf;

class OrganizationCrudController extends AbstractCrudController
{
    public function __construct(
        private RequestStack $requestStack,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Organization::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response =  $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->requestStack->getSession()->get('filterByElement')) {
            $element = $this->requestStack->getSession()->get('filterByElement');
            if ($element instanceof Organization) {
                $response = $response
                    ->where("entity.id = :id")
                    ->setParameter('id', $element->getId());
            } elseif ($element instanceof Event) {
                $response = $response
                    ->where("entity.id = :id")
                    ->setParameter('id', $element->getOrganization()->getId());
            }
        }

        return $response;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
            TextEditorField::new('presentation', 'Présentation')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
            EmailField::new('email'),
            CollectionField::new('urls')->useEntryCrudForm(UrlCrudController::class),
            CollectionField::new('events')->onlyOnDetail()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX, 'Organisations')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une organisation')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une organisation')
            ->setEntityLabelInPlural('organisations')
            ->setEntityLabelInSingular('organisation');
    }

    public function configureActions(Actions $actions): Actions
    {

        $newEvent = Action::new('newEvent', 'Créer un événement')
        ->linkToUrl(function(Organization $organization) {
            return $this->adminUrlGenerator
                ->setController(EventCrudController::class)
                ->setAction(Action::NEW)
                ->set('organization', $organization->getId())
                ->generateUrl();
        });       

        return $actions
            ->add(Crud::PAGE_INDEX, $newEvent)
            ->add(Crud::PAGE_DETAIL, $newEvent)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer une organisation');
            })
            ->reorder(Crud::PAGE_DETAIL, ['newEvent', 'edit', 'delete', 'index'])
            ->reorder(Crud::PAGE_INDEX, ['edit', 'detail', 'newEvent', 'delete']);
        }

   
}
