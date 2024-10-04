<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserTM;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/apirest', name: 'api_')]
class UserController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier, private EntityManagerInterface $em) {}

    #[Route("/user/me", name: "/user/me")]
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

    #[Route('/user/register', name:'add_user', methods:'POST')]
    public function addUser(Request $request)
    {
        $data = $request->toArray();
        $user = new UserTM();

        $user->setEmail($data['email']);
        $user->setTelephone($data['phoneNumber']);
        $user->setName($data['name']);
        $user->setPassword($data['password']);
        $user->setIsVerified(false);
        $this->em->persist($user);
        $this->em->flush();

        try {
            $this->emailVerifier->sendEmailConfirmation($user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@terra-mimbusia.fr', 'Terra Mimbusia'))
                    ->to($user->getEmail())
                    ->subject('Confirmez votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
        } catch (Exception $e ){
            $this->em->remove($user);
            $this->em->flush();
            throw $e;
        }

        return $this->json([], 200);
    }

    #[Route('/user/validate', name: 'validate_user', methods: 'POST')]
    public function validateUser (Request $request)
    {
        $hash = $request->toArray()['emailVerificationHash'];

        if (!isset($hash)) {
            throw new BadRequestHttpException('missing hash');
        }

        $this->emailVerifier->confirmUserAccount($hash);

        return $this->json([], 200);
    }

    #[Route('/user/delete/me', name: 'delete_account', methods: 'DELETE')]
    public function deleteAccount (Request $request, UserPasswordHasherInterface $hasher) 
    {   
        /** @var UserTM */
        $loggedUser = $this->getUser();

        $password = $request->toArray()['password'];

        if (!isset($password)) {
            throw new BadRequestHttpException('missing password');
        }



        if (!$hasher->isPasswordValid($loggedUser, $password)) {
            throw new BadRequestHttpException('invalid password');
        }

        $this->em->remove($loggedUser);
        $this->em->flush();

        return ($this->json([], 200));
    }
}
