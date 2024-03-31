<?php

namespace App\Controller\Api;

use App\Repository\RpgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class RpgController extends AbstractController
{
    #[Route("/rpgs", name: "/rpgs")]
    public function getRpgs (RpgRepository $rpgRepository)
    {
        $rpgs = [];

        foreach ($rpgRepository->findAll() as $rpg) {
            $tags = [];
            $triggerWarnings = [];

            foreach ($rpg->getTags() as $tag) {
                $tags[] = [
                    'id' => $tag->getId(),
                    'tag' => $tag->getTag(),
                ];
            }

            foreach ($rpg->getTriggerWarnings() as $triggerWarning) {
                $triggerWarnings[] = [
                    'id' => $triggerWarning->getId(),
                    'tag' => $triggerWarning->getTheme(),
                ];
            }

            $rpgs[] = [
                'id' => $rpg->getId(),
                'description' => $rpg->getDescription(),
                'name' => $rpg->getName(),
                'publisher' => $rpg->getPublisher(),
                'universe' => $rpg->getUniverse(),
                'tags' => $tags,
                'triggerWarnings' => $triggerWarnings
            ];
        }

        return $this->json($rpgs, 200, [], ['groups' => 'main']);
    }
}
