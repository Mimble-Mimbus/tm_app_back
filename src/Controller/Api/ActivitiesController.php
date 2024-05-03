<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Entertainment;
use App\Entity\EntertainmentReservation;
use App\Entity\EntertainmentSchedule;
use App\Entity\Rpg;
use App\Entity\RpgActivity;
use App\Entity\RpgReservation;
use App\Entity\RpgTable;
use App\Entity\RpgZone;
use App\Entity\Tag;
use App\Entity\TriggerWarning;
use App\Repository\RpgRepository;
use App\Repository\TagRepository;
use App\Repository\TriggerWarningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/api/apirest', name: 'api_')]
class ActivitiesController extends AbstractController
{
    #[Route('/event/{id}/activities', name: '/activities')]
    public function getActivities (Event $event) 
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
                        'availableSeats' =>$schedule->getAvailableSeats()
                    ];
                }

                $type = $entertainment->getEntertainmentType();
                $entertainments[] = [
                    "zoneId" => $zone->getId(),
                    'id' => $entertainment->getId(),
                    'name' => $entertainment->getName(),
                    'schedules' => $schedules,
                    'entertainmentType' => [
                        'id' => $type->getId(),
                        'name' => $type->getName(),
                        'description'=> $type->getDescription(),
                    ]
                ];
            }
        }

        $rpgActivities = [];
        
        foreach($event->getRpgZones() as $zone) {
            $rpgZone = [
                'id' => $zone->getId(),
                'name' => $zone->getName(),
                'eventId' => $zone->getEvent()->getId(),
                'zone' => [
                    'id' => $zone->getZone()->getId(),
                    'name' => $zone->getZone()->getName()
                ]
            ];
            foreach($zone->getRpgActivities() as $activity) {
                $rpgTables = [];
                $user = $activity->getUserGm();

                foreach ($activity->getActivitySchedules() as $rpgTable) {
                    $rpgTables[] = [
                        'id' => $rpgTable->getId(),
                        'start' => $rpgTable->getStart(),
                        'duration' => $rpgTable->getDuration(),
                        'availablesSeats' =>$rpgTable->getAvailableSeats()
                    ];
                }

                $rpgActivities[] = [
                    'rpgZone'=> $rpgZone,
                    'schedules' => $rpgTables,
                    'name' => $activity->getName(),
                    'id' => $activity->getId(),
                    'userGm' => [
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                    ]
                ];
            }
        }

        return $this->json([
            'rpgActivities' => $rpgActivities,
            'entertainments' => $entertainments
        ], 200, [], ['groups' => "main"]);
    }

    #[Route('/entertainment/{id}', name: '/entertainement')]
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
            'schedules' => $schedules,
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

    #[Route("/rpg_reservation/{id}", name: '/rpg_reservation', methods:"POST")]
    public function  postRpgReservation(RpgTable $rpgTable, Request $request, EntityManagerInterface $em)
    {
        $rpgReservation = new RpgReservation();
        $body = $request->toArray();
        $rpgReservation->setPhoneNumber($body['phoneNumber']);
        $rpgReservation->setBookings($body['bookings']);
        $rpgReservation->setName($body['name']);
        $rpgReservation->setEmail($body["email"]);
        $rpgTable->addActivityReservation($rpgReservation);

        $em->persist($rpgReservation);
        $em->persist($rpgTable);
        $em->flush();

        return $this->json([ 'id' => $rpgReservation->getId()], 200, [], ["groups" => "main"]);
    }

    #[Route("/rpg_activity/{id}", methods: ['GET'], name:"/rpg_activity")]
    public function getRpgActivity (RpgActivity $rpgActivity)
    {   
        $schedules = [];
        foreach ($rpgActivity->getActivitySchedules() as $schedule) {
            $schedules[] = [
                'id' => $schedule->getId(),
                'start' => $schedule->getStart(),
                'duration' => $schedule->getDuration(), 
                'availableSeats' => $schedule->getAvailableSeats(),
            ];
        }
        $user = $rpgActivity->getUserGm();
        $response = [
            'id' => $rpgActivity->getId(),
            'name' => $rpgActivity->getName(),
            'description' => $rpgActivity->getDescription(),
            'schedules' => $schedules,
            'userGm' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
            ]
        ];

        return $this->json($response, 200, [], ["groups" => "main"]);
    }

    #[Route("/rpg_activity/{id}", methods:['POST'], name:'post/rpg_activity')]
    public function postRpgActivity (
        RpgZone $rpgZone,
        Request $request, 
        EntityManagerInterface $em, 
        TagRepository $tagRepository, 
        TriggerWarningRepository $triggerWarningRepository,
        RpgRepository $rpgRepository,
        Security $security
    ) {
        $user = $security->getUser();
        $rpgActivity = new RpgActivity();
        $body = $request->toArray();

        $rpgActivity->setName($body['name']);
        $rpgActivity->setDuration(intval(substr($body['duration'], 0, 2)));
        $rpgActivity->setDescription($body['description']);
        $rpgActivity->setMaxNumberSeats($body['maxNumberSeats']);
        $rpgActivity->setRpgZone($rpgZone);
        $rpgActivity->setUserGm($user);

        foreach ($body['schedules'] as $schedule) {
            $rpgTable = new RpgTable();
            $rpgTable->setDuration(intval(substr($body["duration"], 0, 2)));
            $rpgTable->setStart(new DateTime($schedule['start']));
            $rpgActivity->addActivitySchedule($rpgTable);
            $em->persist($rpgTable);
        }

        foreach ($body['tags'] as $tagId) {
            /** @var Tag */
            $tag = $tagRepository->findOneById($tagId);
            $tag->addRpgActivity($rpgActivity);
            $em->persist($tag);
        }

        foreach ($body['triggerWarnings'] as $triggerWarningId) {
            /** @var TriggerWarning */
            $triggerWarning = $triggerWarningRepository->findOneById($triggerWarningId);
            $triggerWarning->addRpgActivity($rpgActivity);
            $em->persist($triggerWarning);
        }

        $rpgData = $body["rpgData"];
        if (is_integer($rpgData)) {
            /** @var Rpg */
            $rpg = $rpgRepository->findOneById($rpgData);
        } else if (is_array($rpgData)) {
            $rpg = new Rpg();
            $rpg->setName($rpgData['name']);
            $rpg->setUniverse($rpgData['universe']);
            $rpg->setPublisher($rpgData['publisher']);
            $rpg->setDescription($rpgData['description']);
            
            foreach ($rpgData['tags'] as $tag) {
                /** @var Tag */
                $tag = $tagRepository->findOneById($tagId);
                $tag->addRpg($rpg);
                $em->persist($tag);
            }

            foreach ($rpgData['triggerWarnings'] as $triggerWarning) {
                /** @var TriggerWarning */
                $triggerWarning = $triggerWarningRepository->findOneById($triggerWarningId);
                $triggerWarning->addRpg($rpg);
                $em->persist($triggerWarning);
            }
        } else {
            throw new HttpException('invalid rpg data');
        }
        
        $rpg->addRpgActivity($rpgActivity);

        $em->persist($rpg);
        $em->persist($rpgActivity);
        $em->flush();

        $schedules = [];
        foreach ($rpgActivity->getActivitySchedules() as $schedule) {
            $schedules[] = [
                'id' => $schedule->getId(),
                'start' => $schedule->getStart(),
                'duration' => $schedule->getDuration(),
                'availableSeats' => $schedule->getAvailableSeats(),
            ];
        }

        $response = [
            'id' => $rpgActivity->getId(),
            'schedules' => $schedules
        ];
        return $this->json($response, 200, [], ['groups' => 'main']);
    }
}
