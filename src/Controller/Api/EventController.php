<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class EventController extends AbstractController
{
    #[Route('/get_event_informations/{id}', name: 'get_event_informations')]
    public function getEventInformations (EventRepository $eventRepository, int $id) {
        /** @var Event */
        $event  = $eventRepository->findOneById($id);

        if(!$event) {
            return new JsonResponse(['error' => "event deosn't existe"], 404);
        }

        $openDays = [];
        $paymentables = [];
        $transits = [];


        foreach ($event->getOpenDays() as $openDay) {
            $openDays[] = [
                'dayStart' => $openDay->getDayStart(),
                'dayEnd' => $openDay->getDayEnd(),
            ];
        }

        foreach ($event->getTransits() as $transit) {
            $transits[] = [
              'name' => $transit->getName(),
              'address' => $transit->getAddress(),
              'start' => $transit->getStart(),
              'arrival' => $transit->getArrival(),
              'availableSeats' => $transit->getAvailableSeats(),
            ];
        }

        foreach ($event->getPaymentables() as $paymentable) {
            $prices = [];

            $type = $paymentable->getTypePaymentable()->getName();

            if ($type !== 'consommable buvette') {
              continue;
            }

            foreach ($paymentable->getPrices() as $price) {
                $prices[] = [
                    'price' =>  $price->getPrice(), 
                    'condition' => $price->getPriceCondition()
                ];
            }

            $paymentables[] = [
              'type' => $type,
              'priceDetails' => $prices,
              'name' => $paymentable->getName()
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
}
