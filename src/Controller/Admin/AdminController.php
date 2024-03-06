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
    #[Route('/ajax/get_calendar', name:'ajax_get_calendar_datas', methods:['GET'])]
    public function ajax_get_calendar_datas(Request $request, UserTMRepository $userTMRepository, ZoneRepository $zoneRepository, EventRepository $eventRepository, OrganizationRepository $organizationRepository, VolunteerShiftRepository $volunteerShiftRepository)
    {
        $entityType = $request->get('filter');
        $shifts = [];
        $planning_events = [];
        
        switch($entityType) {
            case 'volunteer':
                $user = $userTMRepository->find($request->get('id'));
                $shifts = $user->getVolunteerShifts();
                foreach ($shifts as $shift) {
                    $planning_events[] = [
                        'title' => $shift->getZone()->getName(),
                        'start' => date_format($shift->getShiftStart(), 'c'),
                        'end' => date_format($shift->getShiftEnd(), 'c')
                    ];
                }
                break;
            case 'zone' : 
                $zone = $zoneRepository->find($request->get('id'));
                $shifts = $volunteerShiftRepository->findBy(['zone' => $zone]);
                foreach ($shifts as $shift) {
                    $planning_events[] = [
                        'title' => $shift->getUser()->getName(),
                        'start' => date_format($shift->getShiftStart(), 'c'),
                        'end' => date_format($shift->getShiftEnd(), 'c')
                    ];
                }
                break;
            case 'event' :
                $event = $eventRepository->find($request->get('id'));
                $shifts = $volunteerShiftRepository->findBy(['event' => $event]);
                foreach ($shifts as $shift) {
                    $planning_events[] = [
                        'title' => $shift->getZone()->getName() . '-' . $shift->getUser()->getName(),
                        'start' => date_format($shift->getShiftStart(), 'c'),
                        'end' => date_format($shift->getShiftEnd(), 'c')
                    ];
                }
                break;
        }

        
        return new JsonResponse(['events' => $planning_events]);
    }
}
