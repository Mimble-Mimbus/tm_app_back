<?php

namespace App\EventSubscriber;
use App\Entity\UserTM;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface as EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasherInterface,
        private RequestStack $requestStack
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setPassword'],
            BeforeEntityUpdatedEvent::class => ['setRoles']
        ];
    }

    public function setPassword(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof UserTM)){
            return;
        }

        $password = uniqid('tm_') . date_timestamp_get(new \DateTime);
        $entity->setPassword($this->passwordHasherInterface->hashPassword($entity, $password));

        $roles = $entity->getRoles();
        if (!in_array('ROLE_USER', $entity->getRoles())) {
            $roles[] = 'ROLE_USER';
        }

        $entity->setRoles($roles);
    }

    public function setRoles(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof UserTM)){
            return;
        }

        $roles = $this->requestStack->getMainRequest()->request->all()['UserTM']['roles'];
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        $entity->setRoles($roles);
    }
}