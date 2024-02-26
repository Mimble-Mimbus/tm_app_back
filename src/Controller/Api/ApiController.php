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
}
