<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class EventController extends AbstractController
{   
    #[Route('/get_event_informations/{id}', name: 'get_event_informations')]
    public function getEventInformations (Event $event) 
    {
        $openDays = [];
        $paymentables = [];
        $transits = [];

        foreach ($event->getOpenDays() as $openDay) {
            $openDays[] = [
                'id' => $openDay->getId(),
                'dayStart' => $openDay->getDayStart(),
                'dayEnd' => $openDay->getDayEnd(),
            ];
        }

        foreach ($event->getTransits() as $transit) {
            $transits[] = [
                'id' => $transit->getId(),
                'name' => $transit->getName(),
                'address' => $transit->getAddress(),
                'start' => $transit->getStart(),
                'arrival' => $transit->getArrival(),
                'availableSeats' => $transit->getAvailableSeats(),
            ];
        }

        foreach ($event->getPaymentables() as $paymentable) {
            $prices = [];

            $type = $paymentable->getTypePaymentable();

            if ($type->getName() !== 'consommable buvette') {
              continue;
            }

            foreach ($paymentable->getPrices() as $price) {
                $prices[] = [
                    'id' => $price->getId(),
                    'price' =>  $price->getPrice(), 
                    'condition' => $price->getPriceCondition()
                ];
            }

            $paymentables[] = [
                'id' => $paymentable->getId(),
                'type' => $type,
                'priceDetails' => $prices,
                'name' => $paymentable->getName(),
                'typePaymentable' => [
                    'id' => $type->getId(),
                    'name' => $type->getName(),
                ]
            ];
        }

        $response = [
            'id' => $event->getId(),
            'paymentables' => $paymentables,
            "openDays" => $openDays,
            'transits' => $transits,
            'address' => $event->getAddress()
        ];

        return $this->json($response, 200, [], ["groups" => "main"]);
    }

    private function getBaseEventData (Event $event)
    {
        $zones = [];
        $rpgZones = [];

        foreach ($event->getZones() as $zone) {
            $zones[] = [
                'id' => $zone->getId(),
                'name' => $zone->getId(),
            ];
        }

        foreach ($event->getRpgZones() as $rpgZone) {
            $rpgZones[] = [
                'id' => $rpgZone->getId(),
                'name' => $rpgZone->getName(),
                'zoneId' => $rpgZone->getZone()->getId(),
            ];
        }

        $response = [
            'address' => $event->getAddress(),
            'id' => $event->getId(),
            'zones'=> $zones,
            'rpgZones' => $rpgZones,
        ];

        return $response;
    }

    #[Route('/next_event', name: 'next_event')]
    public function getNextEvent (EventRepository $eventRepository)
    {

        $event = $eventRepository->findNextEvent();

        $response = $this->getBaseEventData($event);

        return $this->json($response, 200, [], ["groups" => "main"]);
    }

    #[Route('/event/{id}', name: 'event')]
    public function getEvent (Event $event) 
    {
        $response = $this->getBaseEventData($event);

        return $this->json($response, 200, [], ["groups" => "main"]);
    }
}
