<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Quest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class QuestController extends AbstractController
{ 
    #[Route("/event/{id}/quests", name: "/quests")]
    public function getQuests (Event $event)
    {
        $response = [];

        foreach ($event->getQuests() as $quest) {
            if ($quest->isIsSecret()) continue;

            $zone = $quest->getZone();

            $response[] = [
              'id' => $quest->getId(),
              'title' => $quest->getTitle(),
              'infos' => $quest->getInfos(),
              'points' => $quest->getPoints(),
              'zone' => [
                  'id' => $zone->getId(),
                  'name' => $zone->getName(),
              ],
              'type' => $quest->getType(),
            ];
        }

        return $this->json($response, 200, [], ['groups' => 'main']);
    }

    #[Route("/quest/{id}", name: "/quest")]
    public function getQuest (Quest $quest)
    {
        $zone = $quest->getZone();
        $response = [
          'id' => $quest->getId(),
          'title' => $quest->getTitle(),
          'infos' => $quest->getInfos(),
          'points' => $quest->getPoints(),
          'zone' => [
              'id' => $zone->getId(),
              'name' => $zone->getName(),
          ],
          'type' => $quest->getType(),
        ];

        

        return $this->json($response, 200, [], ['groups' => 'main']);
    }
}
