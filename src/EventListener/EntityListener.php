<?php

namespace App\EventListener;

use App\Service\ValidatorService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\Exception\HttpException;


#[AsDoctrineListener(event: Events::prePersist)]
class EntityListener 
{   
    public function __construct(
        public ValidatorService $validatorService
    ) {}

    public function prePersist (PrePersistEventArgs $object)
    {
        $entity = $object->getObject();

        $this->validatorService->validate($entity);
    }
}