<?php

namespace App\EventListener;

use App\Entity\UserTM;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: UserTM::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: UserTM::class)]
class UserTMListener 
{   
    public function __construct(
      public EntityManagerInterface $em ,
      public UserPasswordHasherInterface $passwordHasher
    ) {}

    public function prePersist (UserTM $user)
    {
        $this->hashPassword($user);
    }

    public function preUpdate(UserTM $user, PreUpdateEventArgs $event) 
    {
        if ($event->hasChangedField('password')) {
            $this->hashPassword($user);
        }
    }

    private function hashPassword (UserTM $user)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    }
}