<?php

namespace App\EventListener;

use App\Entity\UserTM;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTMListener 
{   
    public function __construct(
      public EntityManagerInterface $em ,
      public UserPasswordHasherInterface $passwordHasher
    ) {}

    public function prePersist (UserTM $user)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    }
}