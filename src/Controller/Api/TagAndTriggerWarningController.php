<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use App\Repository\TriggerWarningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class TagAndTriggerWarningController extends AbstractController
{
    #[Route("/tags_and_trigger_warnings", name: "/tags_and_trigger_warnings")]
    public function getTagsAndTriggerWarnings (TriggerWarningRepository $triggerWarningRepository, TagRepository $tagRepository)
    {
        $triggerWarnings = [];
        $tags = [];

        foreach ($triggerWarningRepository->findAll() as $triggerWarning) {
            $triggerWarnings[] = [
                'id' => $triggerWarning->getId(),
                'theme' => $triggerWarning->getTheme(), 
            ];
        }

        foreach ($tagRepository->findAll() as $tag) {
            $tags[] = [
                'id' => $tag->getId(),
                'tag' => $tag->getTag(),
            ];
        }

        return $this->json([
            'tags' => $tags,
            'triggerWarnings' => $triggerWarnings
        ], 200, [], ['groups' => 'main']);
    }
}
