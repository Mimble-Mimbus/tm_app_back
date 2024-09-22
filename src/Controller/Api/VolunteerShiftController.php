<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\VolunteerShiftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserTM;

#[Route('/api/apirest', name: 'api_')]
class VolunteerShiftController extends AbstractController
{
    public function __construct(
        private VolunteerShiftRepository $volunteerShiftRepository
    ) {}

    #[Route('/event/{id}/volunteer_shifts', name: '/volunteer_shifts')]
    public function shifts (Event $event)
    {
        /** @var UserTM */
        $user = $this->getUser();

        $volunteerShifts = $this->volunteerShiftRepository->getShiftsForPlanning($event->getId(), null, $user->getId());
        $response = [];
        
        foreach ($volunteerShifts as $volunteerShift) {
            $zone = $volunteerShift->getZone();
            $response[] = [
                'id' => $volunteerShift->getId(),
                'description' => $volunteerShift->getDescription(),
                'shiftStart' => $volunteerShift->getShiftStart(),
                'shiftEnd' => $volunteerShift->getShiftEnd(),
                'zone' => [
                    'id' => $zone->getId(),
                    'name' => $zone->getName(),
                ]
            ];
        }

        return $this->json($response);
    }     
}