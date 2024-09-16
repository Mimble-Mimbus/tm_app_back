<?php

namespace App\Controller\Api;

use App\Service\HelloAssoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/apirest', name: 'api_')]
class HelloAssoController extends AbstractController
{
    public function __construct(
        private HelloAssoService $helloAssoService
    ) {}

    #[Route("/verify_ticket", name: "/verify_ticket", methods: 'POST')]
    public function verify (Request $request)
    {
        $qrCode = $request->toArray()['qrCode'];
        $this->helloAssoService->verify();
        $ticket = $this->helloAssoService->getTicket($qrCode);

        if (isset($ticket)) {
            return $this->json($ticket);
        } else {
            throw new BadRequestException('invalid id');
        }
    }
}
