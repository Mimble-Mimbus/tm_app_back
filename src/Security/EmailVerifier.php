<?php

namespace App\Security;

use App\Entity\UserTM;
use App\Repository\UserTMRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class EmailVerifier
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private UserTMRepository $userRepository,
    ) {
    }

    public function sendEmailConfirmation(UserTM $user, TemplatedEmail $email): void
    {
        $emailVerificationHash = md5(uniqid($user->getEmail().time(), true));
        $context = $email->getContext();
        $timeDiff = 15;
        $date = new DateTime();
        $date->add(new DateInterval('PT'.$timeDiff.'M'));
        $url = $_ENV['FRONT_URL'].'/verify-email?verificationhash='.$emailVerificationHash;
        $context['url'] = $url;
        $context['expireAt'] = $timeDiff.' min';
        $context['name'] = $user->getName();

        $email->context($context);

        $user->setEmailVerificationHash($emailVerificationHash);
        $user->setEmailVerificationHashExpireAt($date);
        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function confirmUserAccount(string $hash): void
    {
        $user = $this->userRepository->getUserByHash($hash);
        if (!$user) {
            throw new BadRequestHttpException('invalid hash');
        }

        $time = new DateTime();

        if ($time > $user->getEmailVerificationHashExpireAt()) {
            throw new BadRequestHttpException('expired hash');
        }
        
        $user->setIsVerified(true);
        $user->setEmailVerificationHash(null);
        $user->setEmailVerificationHashExpireAt(null);
        $user->setRoles(['ROLE_USER', 'ROLE_VISITOR']);

        $this->em->persist($user);
        $this->em->flush();
    }
}
