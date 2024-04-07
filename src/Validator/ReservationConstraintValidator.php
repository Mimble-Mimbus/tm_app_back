<?php

namespace App\Validator;

use App\Entity\Entertainment;
use App\Entity\EntertainmentReservation;
use App\Entity\RpgReservation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ReservationConstraintValidator extends ConstraintValidator
{
  public const PHONE_NUMBER_PATTERN = "/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/";

  public function validate ($protocol, Constraint $constraint): void
  {
      if (!$constraint instanceof ReservationConstraint) {
          throw new UnexpectedTypeException($constraint, ReservationConstraint::class);
      }

      if (!($protocol instanceof RpgReservation || $protocol instanceof EntertainmentReservation)) {
          throw new UnexpectedValueException($protocol, 'rpgReservation | entertainementReservation');
      }

      if (!$this->validatePhoneNumber($protocol->getPhoneNumber())) {
          $this->context->buildViolation($constraint->phoneNumberMessage)
              ->atPath('phoneNumner')
              ->setParameter('{{value}}', $protocol->getPhoneNumber())
              ->addViolation();
      }

      if ($protocol instanceof RpgReservation) {
          if(!$this->validateRpg($protocol)) {
              $this->context->buildViolation($constraint->emailMessage)
                  ->atPath("email")
                  ->setParameter('{{activity}}', $protocol->getRpgTable()->getRpgActivity()->getName())
                  ->setParameter('{{email}}', $protocol->getEmail())
                  ->addViolation();
          };
      }

      if ($protocol instanceof EntertainmentReservation) {
          if(!$this->validateEntertainement($protocol)) {
              $this->context->buildViolation($constraint->emailMessage)
                  ->atPath("email")
                  ->setParameter('{{activity}}', $protocol->getEntertainmentSchedule()->getEntertainment()->getName())
                  ->setParameter('{{email}}', $protocol->getEmail())
                  ->addViolation();
          };
      }

  }

  private function validateRpg(RpgReservation $rpgReservation)
  {
      $rpgTable = $rpgReservation->getRpgTable();

      $email = $rpgReservation->getEmail();
      foreach ($rpgTable->getActivityReservations() as $reservation) {
          if (($reservation !== $rpgReservation) && ($reservation->getEmail() === $email)) {
              return false;
          }
      }

      return true;
  }

  private function validateEntertainement(EntertainmentReservation $entertainmentReservation)
  {
      $entertainmentSchedule = $entertainmentReservation->getEntertainmentSchedule();

      if ($entertainmentSchedule) {
          $email = $entertainmentReservation->getEmail();
          foreach ($entertainmentSchedule->getActivityReservations() as $reservation) {
              if (($reservation !== $entertainmentReservation) && ($reservation->getEmail() === $email)) {
                  return false;
              }
          }
      }

      return true;
  }

  private function validatePhoneNumber(string $str)
  {
    $onlyNumber = preg_replace('/[^0-9]/', '', $str);

    return boolval(preg_match(self::PHONE_NUMBER_PATTERN, $onlyNumber));  
  }
}