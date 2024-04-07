<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;


/**
 * @property ConstraintViolationInterface[] $violations
 */
class ConstraintException extends BadRequestException {
public $violations = [];
public function __construct(ConstraintViolationListInterface $violations, public string $entityName)
  { 
      parent::__construct('violation of constraints', 400);
      foreach ($violations as $violation) {
          $this->violations[] = $violation;
      }
  }
}