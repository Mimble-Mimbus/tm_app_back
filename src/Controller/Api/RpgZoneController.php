<?php

namespace App\Controller\Api;

use App\Entity\RpgZone;
use App\Repository\RpgTableRepository;
use App\Repository\RpgZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class RpgZoneController extends AbstractController
{
    #[Route("/rpg_zone/{id}", name: "/rpg_zone")]
    public function getRpgZone (RpgZone $rpgZone)
    {
        $rgpTables = [];
        foreach ($rpgZone->getRpgActivities() as $rpgActivity) {
            foreach ($rpgActivity->getActivitySchedules() as $rpgTable) {
                $rgpTables[] = [
                    'id' => $rpgTable->getId(),
                    'start' => $rpgTable->getStart(),
                    'duration' => $rpgTable->getDuration(),
                    'availableSeats' => $rpgTable->getAvailableSeats(),
                ];
            }
        }

        $openDays = []; 
        foreach ($rpgZone->getEvent()->getOpenDays() as $openDay) {
            $openDays[] = [
                'id' => $openDay->getId(),
                'dayStart' => $openDay->getDayStart(),
                'dayEnd' => $openDay->getDayEnd(),
            ];
        }

        $response = [
            'id' => $rpgZone->getId(),
            'rpgTables' => $rgpTables,
            'maxAvailableSeatsPerTable' => $rpgZone->getMaxAvailableSeatsPerTable(),
            'minStartHour' => $rpgZone->getMinStartHour(),
            'maxEndHour' => $rpgZone->getMaxEndHour(),
            'availableTables' => $rpgZone->getAvailableTables(),
            'openDays' => $openDays
        ];

        return $this->json($response, 200, [], ['groups' => 'main']);
    }
}
