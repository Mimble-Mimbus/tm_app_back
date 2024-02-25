<?php

namespace App\Controller\Admin;

use App\Repository\TagRepository;
use App\Repository\TriggerWarningRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajax/autocomplete', name: 'admin_ajax_autocomplete_')]
class AutocompleteController extends AbstractController
{
    #[Route('/tags', name: 'tags')]
    public function autocomplete_tags(TagRepository $tagRepository, Request $request) 
    {
        $search = $request->query->get('query');
        $tags = $tagRepository->findAll();
        $list = [];

        foreach ($tags as $tag) {
            if (str_contains($tag->getTag(), $search)) {
                $list['results'][] = ['value' => $tag->getId(), 'text' => $tag->getTag()];
            }
        }

        $results = json_encode($list);

        return new JsonResponse($results, 200, [], true);
    }

    #[Route('/triggers', name: 'triggers')]
    public function autocomplete_triggers(TriggerWarningRepository $triggerWarningRepository, Request $request)
    {
        $search = $request->query->get('query');

        $twlist = $triggerWarningRepository->findAll();
        $list = [];
        foreach ($twlist as $tw) {
            if (str_contains($tw->getTheme(), $search)) {
                $list['results'][] = ['value' => $tw->getId(), 'text' => $tw->getTheme()];
            }
        }

        $results = json_encode($list);

        return new JsonResponse($results, 200, [], true);
    }
}