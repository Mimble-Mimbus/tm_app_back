<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserTM;

#[Route('/api/apirest', name: 'api_')]
class UserController extends AbstractController
{
    #[Route("/user", name: "/user")]
    public function user ()
    {
        /** @var UserTM */
        $user = $this->getUser();
        if (!$user)   {
            throw new HttpException(401, 'invalid creditentials');
        }

        $response = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roles' => $user->getRoles(),
        ];

        return $this->json($response, 200, [], ['groups' => 'main']);
    }
}
