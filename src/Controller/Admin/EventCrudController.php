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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\RequestStack;

class EventCrudController extends AbstractCrudController
{
    public function __construct(
        private OrganizationRepository $organizationRepository,
        private RequestStack $requestStack,
        private AdminUrlGenerator $adminUrlGenerator
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

        $fields = [];
        switch ($pageName) {
            case 'new':
            case 'edit':
            case 'detail':
                $fields = [
                    FormField::addTab('Informations générales'),
                    $org_field,
                    TextField::new('name', 'Nom'),
                    TextEditorField::new('presentation', 'Présentation')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
                    CollectionField::new('urls', 'Urls')->useEntryCrudForm(UrlCrudController::class),
                    CollectionField::new('openDays', "Jours d'ouverture")->useEntryCrudForm(OpenDayCrudController::class)->setHelp("Date et horaires de chaque jour d'ouverture"),

                    FormField::addTab('Zones'),
                    CollectionField::new('zones')->useEntryCrudForm(ZoneCrudController::class),

                    FormField::addTab('Guildes'),
                    CollectionField::new('guilds', 'Guildes')->useEntryCrudForm(GuildCrudController::class),

                    FormField::addTab('Facturables'),
                    CollectionField::new('paymentables', 'Facturables')->useEntryCrudForm(PaymentableCrudController::class)
                ];
                break;
            case 'index':
                $fields = [
                    TextField::new('name', 'Nom')->setTemplatePath('bundles/easyadmin/fields/text_linktodetail.html.twig'),
                    TextEditorField::new('presentation', 'Présentation')->setTemplatePath('bundles/easyadmin/fields/texteditor.html.twig'),
                    $org_field,
                    CollectionField::new('urls', 'Urls')->useEntryCrudForm(UrlCrudController::class),
                    CollectionField::new('openDays', "Jours d'ouverture")->useEntryCrudForm(OpenDayCrudController::class)
                ];
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $manageRpgZones = Action::new('manageRpgZones', 'Gérer les zones RPG')
            ->linkToUrl(function (Event $event) {
                return $this->adminUrlGenerator
                    ->setController(RpgZoneCrudController::class)
                    ->setAction('index')
                    ->set('event', $event->getId())
                    ->unset('entityId')
                    ->generateUrl();
            });

        $managePaymentables = Action::new('managePaymentables', 'Gérer les facturables')
            ->linkToUrl(function (Event $event) {
                return $this->adminUrlGenerator
                    ->setController(PaymentableCrudController::class)
                    ->setAction('index')
                    ->set('event', $event->getId())
                    ->unset('entityId')
                    ->generateUrl();
            });

        $manageGuilds = Action::new('manageGuilds', 'Gérer les guildes')
            ->linkToUrl(function (Event $event) {
                return $this->adminUrlGenerator
                    ->setController(GuildCrudController::class)
                    ->setAction('index')
                    ->set('event', $event->getId())
                    ->unset('entityId')
                    ->generateUrl();
            });

        $manageQuests = Action::new('manageQuests', 'Gérer les quêtes')
            ->linkToUrl(function (Event $event) {
                return $this->adminUrlGenerator
                    ->setController(QuestCrudController::class)
                    ->setAction('index')
                    ->set('event', $event->getId())
                    ->unset('entityId')
                    ->generateUrl();
            });


        return $actions
            ->add(Crud::PAGE_INDEX, $managePaymentables)
            ->add(Crud::PAGE_INDEX, $manageRpgZones)
            ->add(Crud::PAGE_INDEX, $manageGuilds)
            ->add(Crud::PAGE_INDEX, $manageQuests)
            ->add(Crud::PAGE_DETAIL, $managePaymentables)
            ->add(Crud::PAGE_DETAIL, $manageRpgZones)
            ->add(Crud::PAGE_DETAIL, $manageGuilds)
            ->add(Crud::PAGE_DETAIL, $manageQuests)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setLabel('Créer un événement');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('événements')
            ->setEntityLabelInSingular('événement')
            ->setPageTitle(Crud::PAGE_INDEX, 'Evénements')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un événement')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un événement');
    }
}
