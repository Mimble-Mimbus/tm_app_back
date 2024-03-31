<?php

namespace App\EventListener;

use App\Exception\ConstraintException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


#[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable()->getPrevious();
       
        if ($exception instanceof ConstraintException) {
            $properties = [];
            foreach ($exception->violations as $violation) {
                $properties[] = [
                    'name' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                    'value' => (string) $violation->getInvalidValue(),
                    'constraint' => get_class($violation->getConstraint())
                ];
            }

            $response = [
              'statusCode' => $exception->getCode(),
              'message' => $exception->getMessage(),
              'type' => 'ConstraintError',
              'details' => [
                  'entity' => $exception->entityName,
                  'properties' => $properties
              ]
            ];

            $event->setResponse(new JsonResponse($response));
        };
    }
}