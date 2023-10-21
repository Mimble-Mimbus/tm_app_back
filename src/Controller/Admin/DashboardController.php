<?php

namespace App\Controller\Admin;

use App\Entity\Entertainment;
use App\Entity\Event;
use App\Entity\Guild;
use App\Entity\Organization;
use App\Entity\Paymentable;
use App\Entity\Quest;
use App\Entity\RpgActivity;
use App\Entity\RpgZone;
use App\Entity\UserTM;
use App\Entity\VolunteerShift;
use App\Entity\Zone;
use App\Repository\EventRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserTMRepository;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UserTMRepository $userTMRepository,
        private EventRepository $eventRepository,
        private OrganizationRepository $organizationRepository,
        private RequestStack $requestStack
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // sélection d'une organisation ou d'un événement pour filtrer les stats et données
        $chosenFilter = null;
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest->query->get('filter-datas') && $currentRequest->query->get('filter-value')) {
            $repo = null;
            switch ($currentRequest->query->get('filter-datas')) {
                case 'organization':
                    $repo = $this->organizationRepository;
                    break;
                case 'event':
                    $repo = $this->eventRepository;
                    break;
            }
            $chosenFilter = $repo->findOneBy(['id' => intval($currentRequest->query->get('filter-value'))]);
            $currentRequest->getSession()->set('filterByElement', $chosenFilter);
        }



        // statistiques
        $users = $this->userTMRepository->count([]);
        $events = $this->eventRepository->findAll();
        $currentEvent = null;
        $next_event = null;
        $next_event_open_day = null;
        foreach ($events as $event) {
            foreach ($event->getOpenDays() as $openDay) {
                if ($openDay->getDayStart() <= new DateTime() && $openDay->getDayEnd() >= new DateTime()) {
                    $currentEvent = $event;
                } elseif ($openDay->getDayStart() > new DateTime()) {
                    if ($next_event_open_day == null || $next_event_open_day > $openDay->getDayStart()) {
                        $next_event_open_day = $openDay->getDayStart();
                        $next_event = $event;
                    }
                }
            }
        }


        return $this->render('/bundles/easyadmin/dashboard.html.twig', [
            'users_count' => $users,
            'next_event' => $next_event,
            'current_event' => $currentEvent,
            'chosen_filter' => $currentRequest->getSession()->get('filterByElement')
        ]);
    }

    #[Route('/admin/get-list/{type}', name: 'admin-dashboard-get-list')]
    public function dashboard_get_list($type)
    {
        $elements = null;
        switch ($type) {
            case "organization":
                $elements = $this->organizationRepository->findAll();
                break;
            case "event":
                $elements = $this->eventRepository->findAll();
                break;
        }

        return $this->render("bundles/easyadmin/dashboard_includes/select_filter.html.twig", [
            'list' => $elements
        ]);
    }

    #[Route('/admin/reset-filter', name: 'admin_dashboard_reset_filter')]
    public function dashboard_reset_filter()
    {
        $this->requestStack->getSession()->remove('filterByElement');
        return $this->redirectToRoute('admin');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord')
            ->setTranslationDomain('admin');
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addWebpackEncoreEntry('admin');

        return $assets;
    }

    public function configureActions(): Actions
    {
        return Actions::new()
            ->addBatchAction(Action::BATCH_DELETE)
            ->add(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DELETE)

            ->add(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_DETAIL, Action::INDEX)
            ->add(Crud::PAGE_DETAIL, Action::DELETE)

            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)

            ->add(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::section('Général');
        yield MenuItem::linkToCrud('Organisations', 'fa-solid fa-sitemap', Organization::class);
        yield MenuItem::linkToCrud('Evènements', 'fa-solid fa-calendar-days', Event::class);
        yield MenuItem::linkToCrud('Facturables', 'fa-solid fa-money-bill-wave', Paymentable::class);
        yield MenuItem::section('Lore');
        yield MenuItem::linkToCrud('Guildes', 'fa-solid fa-shield', Guild::class);
        yield MenuItem::linkToCrud('Zones', 'fa-solid fa-map-location-dot', Zone::class);
        yield MenuItem::linkToCrud('RpgZone', 'fa-solid fa-dice-d20', RpgZone::class);
        yield MenuItem::linkToCrud('Quêtes', 'fa-solid fa-horse', Quest::class);
        yield MenuItem::section('Activités');
        yield MenuItem::linkToCrud('Animations', 'fa-solid fa-hat-wizard', Entertainment::class);
        yield MenuItem::linkToCrud('Parties de JDR', 'fa-solid fa-dungeon', RpgActivity::class);
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Comptes', 'fa-solid fa-users', UserTM::class);
        yield MenuItem::linkToCrud('Bénévoles', 'fa-solid fa-people-group', VolunteerShift::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
