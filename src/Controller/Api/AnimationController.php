<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Entertainment;
use App\Entity\EntertainmentReservation;
use App\Entity\EntertainmentSchedule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class AnimationController extends AbstractController
{   
    #[Route('/event/{id}/rpg_activities', name: '/rpg_activities')]
    public function getRpgActivity (Event $event) 
    {
        $rpgActivities = [];

        foreach($event->getRpgZones() as $zone) {
            foreach($zone->getRpgActivities() as $activity) {
                $rpgTables = [];
                $user = $activity->getUserGm();

                foreach ($activity->getActivitySchedules() as $rpgTable) {
                    $rpgTables[] = [
                        'id' => $rpgTable->getId(),
                        'start' => $rpgTable->getStart(),
                        'duration' => $rpgTable->getDuration(),
                    ];
                }

                $rpgActivities[] = [
                    'rpgTables' => $rpgTables,
                    'name' => $activity->getName(),
                    'id' => $activity->getDescription(),
                    'userGm' => [
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                    ]
                ];
            }
        }

        return $this->json($rpgActivities, 200, [], ["groups" => "main"]);
    }

    #[Route('/event/{id}/entertainements', name: '/entertainements')]
    public function getAnimations (Event $event) 
    {
        $entertainments = [];

        foreach($event->getZones() as $zone) {
            foreach($zone->getEntertainments() as $entertainment) {
                $schedules = [];

                foreach ($entertainment->getActivitySchedules() as $schedule) {
                    $schedules[] = [
                        'id' => $schedule->getId(),
                        'start' => $schedule->getStart(),
                        'duration' => $schedule->getDuration(),
                    ];
                }

                $type = $entertainment->getEntertainmentType();
                $entertainments[] = [
                    'id' => $entertainment->getId(),
                    'name' => $entertainment->getName(),
                    'entertainmentSchedules' => $schedules,
                    'entertainmentType' => [
                        'id' => $type->getId(),
                        'name' => $type->getName(),
                        'description'=> $type->getDescription(),
                    ]
                ];
            }
        }

        return $this->json($entertainments, 200, [], ["groups" => "main"]);
    }

    #[Route('/entertainement/{id}', name: '/entertainement')]
    public function getAnimation (Entertainment $entertainment)
    {   
        $type = $entertainment->getEntertainmentType();
        $schedules = [];

        foreach ($entertainment->getActivitySchedules() as $schedule) {
          $schedules[] = [
              'id' => $schedule->getId(),
              'start' => $schedule->getStart(),
              'duration' => $schedule->getDuration(), 
              'availableSeats' => $schedule->getAvailableSeats(),
          ];
        }

        $response = [
            'id' => $entertainment->getId(),
            'name' => $entertainment->getName(),
            'description' => $entertainment->getDescription(),
            'entertainmentSchedules' => $schedules,
            'entertainmentType' => [
                'id' => $type->getId(),
                'name' => $type->getName(),
                'description'=> $type->getDescription(),
            ]
        ];

        return $this->json($response, 200, [], ["groups" => "main"]);
    }


    #[Route("/entertainment_reservation/{id}", name: '/entertainment_reservation', methods:"POST")]
    public function  postEntertainmentReservation(EntertainmentSchedule $entertainmentSchedule, Request $request, EntityManagerInterface $em)
    { 
        $entertainmentReservation = new EntertainmentReservation();
        $body = $request->toArray();
        
        $entertainmentReservation->setPhoneNumber($body['phoneNumber']);
        $entertainmentReservation->setBookings($body['bookings']);
        $entertainmentReservation->setName($body['name']);
        $entertainmentReservation->setEmail($body["email"]);
        $entertainmentSchedule->addActivityReservation($entertainmentReservation);

        $em->persist($entertainmentReservation);
        $em->persist($entertainmentSchedule);
        $em->flush();

        return $this->json([ 'id' => $entertainmentReservation->getId()], 200, [], ["groups" => "main"]);
    }
}
