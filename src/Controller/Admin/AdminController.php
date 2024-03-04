<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\OrganizationRepository;
use App\Repository\UserTMRepository;
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
    public function ajax_get_calendar_datas(Request $request, UserTMRepository $userTMRepository, ZoneRepository $zoneRepository, EventRepository $eventRepository, OrganizationRepository $organizationRepository)
    {
        $entityType = $request->get('filter');
        $repository = null;
        
        switch($entityType) {
            case 'volunteer':
                $repository = $userTMRepository;
                break;
            case 'zone' : 
                $repository = $zoneRepository;
                break;
            case 'event' :
                $repository = $eventRepository;
                break;
            case 'organization':
                $repository = $organizationRepository;
                break;
        }
        $entity = $repository->find($request->get('id'));

        $datas = 'hello';

        return new JsonResponse(['datas' => $datas]);
    }
}
