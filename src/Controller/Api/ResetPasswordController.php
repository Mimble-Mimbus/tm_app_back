<?php

namespace App\Controller\Api;

use App\Repository\UserTMRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/api/apirest')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $em,
        private UserTMRepository $userRepository
    ) {
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('/reset-password-request', name: 'reset_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator)
    {
        $data = $request->toArray();
        $email = $data['email'];
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        if (!$user) {
            return $this->json([], 200);
        }

        $this->processSendingPasswordResetEmail(
            $user,
            $mailer,
            $translator
        );

        return $this->json([], 200);
    }

    #[Route('/reset-password', name: 'reset_password', methods: 'POST')]
    public function reset(Request $request)
    {
        $data = $request->toArray();
        $token = $data['token'];
        $password = $data['password'];

        if (!$password) {
            throw new BadRequestHttpException('missing password');
        }

        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        
        if (!$user) {
            throw new BadRequestHttpException('invalid token');
        }
        
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();

        $this->resetPasswordHelper->removeResetRequest($token);

        return $this->json([], 200); 
    }

    private function processSendingPasswordResetEmail($user, MailerInterface $mailer)
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        $url = $_ENV['FRONT_URL'] . '/reset-password?token=' . $resetToken->getToken();

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@terra-mimbusia.fr', 'Terra Mimbusia'))
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'url' => $url,
            ])
        ;

        $mailer->send($email);
    }
}
