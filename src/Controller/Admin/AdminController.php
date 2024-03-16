<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserTMRepository;
use App\Repository\VolunteerShiftRepository;
use App\Repository\ZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name:'admin_')]
class AdminController extends AbstractController
{
    public function __construct(
        private UserTMRepository $userTMRepository,
        private ZoneRepository $zoneRepository,
        private EventRepository $eventRepository,
        private VolunteerShiftRepository $volunteerShiftRepository
    )
    {}

    #[Route('/ajax/get_calendar', name:'ajax_get_calendar_datas', methods:['GET'])]
    public function ajax_get_calendar_datas(Request $request, VolunteerShiftRepository $volunteerShiftRepository)
    {
        $planning_events = [];

        $event_filter = null;
        $zone_filter = null;
        $volunteer_filter = null;
        
        $event_filter = $request->query->get('event') ? $request->query->get('event'): $event_filter;
        $zone_filter = $request->query->get('zone') ? $request->query->get('zone') : $zone_filter;
        $volunteer_filter = $request->query->get('volunteer') ? $request->query->get('volunteer') : $volunteer_filter;

        $filtered_shifts = $volunteerShiftRepository->getShiftsForPlanning($event_filter, $zone_filter, $volunteer_filter);
        
        $start = null;
        if (count($filtered_shifts) > 0) {

            foreach ($filtered_shifts  as $shift) {
                $planning_events[] = [
                    'title' => $shift->getZone()->getName() . '-' . $shift->getUser()->getName(),
                    'start' => date_format($shift->getShiftStart(), 'c'),
                    'end' => date_format($shift->getShiftEnd(), 'c')
                ];
            }
            
            $start = $planning_events[0]['start'];
        }
        
        return new JsonResponse(['events' => $planning_events, 'start' => $start]);
    }

    #[Route('/ajax/set_filters', name:'ajax_set_filters', methods:['GET'])]
    public function ajax_set_filters(Request $request) {
        
        $selected_event = $request->query->get('event');
        $selected_zone = $request->query->get('zone');
        $selected_volunteer = $request->query->get('volunteer');


        $events = $this->eventRepository->findAll();
        $zones = [];
        $volunteers = [];
        $event_filter = null;
        $zone_filter = null;

        if ($selected_event) {
            $zones = $this->zoneRepository->findBy(['event' => $selected_event]);
            $event_filter = $selected_event;
        }

        if ($selected_zone) {
            $volunteer_shifts = $this->volunteerShiftRepository->findBy(['zone' => $selected_zone]);
            foreach ($volunteer_shifts as $volunteer_shift) {
                $volunteers[] = $volunteer_shift->getUser();
            }
            $zone_filter = $selected_zone;
        }

        if (count($zones) == 0) {
            $zones = $this->zoneRepository->findAll();
        }
        
        if (count($volunteers) == 0) {
            $volunteer_shifts = $this->volunteerShiftRepository->findAll();
            foreach ($volunteer_shifts as $volunteer_shift) {
                $volunteers[] = $volunteer_shift->getUser();
            }
        }


        return $this->render('bundles/easyadmin/volunteer_plannings/_filtres.html.twig', [
            'events' => $events,
            'zones' => $zones,
            'volunteers' => $volunteers,
            'event_filter' => $event_filter,
            'zone_filter' => $zone_filter,
            'volunteer_filter' => $selected_volunteer,
        ]);
    }
    
}
