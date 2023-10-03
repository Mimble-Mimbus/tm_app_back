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
use App\Repository\UserTMRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private UserTMRepository $userTMRepository,
        private EventRepository $eventRepository
    ) {
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // TODO permettre de sélectionner une organisation ou un événement pour filtrer les stats et données

        $users = $this->userTMRepository->count([]);
        $nextEvent = $this->eventRepository->findNextEvent()[0];


        return $this->render('/bundles/easyadmin/dashboard.html.twig', [
            'users_count' => $users,
            'next_event' => $nextEvent
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord')
            ->setTranslationDomain('admin');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addHtmlContentToHead('<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>');
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
