<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\OrganizationRepository;
use PHPUnit\Framework\Constraint\ExceptionMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\throwException;

#[Route('/api/apirest', name: 'api_')]
class ApiController extends AbstractController
{

    #[Route('/get_organization/{id}', name: 'get_organization')]
    public function get_organization(OrganizationRepository $organizationRepository, int $id)
    {
        $organization = $organizationRepository->findOneById($id);

        if ($organization) {
            $urls = [];
            foreach ($organization->getUrls() as $url) {
                $urls[] = [$url->getName() => $url->getUrl()];
            }

            $response = [
                'name' => $organization->getName(),
                'description' => $organization->getPresentation(),
                'urls' => $urls,
                'email' => $organization->getEmail()
            ];
            return $this->json($response, 200, [], ["groups" => "main"]);
        } else {
            return new JsonResponse(['error' => "L'organisation recherchée n'existe pas."]);
        }
    }


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
