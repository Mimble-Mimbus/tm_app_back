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
  public function validate ($protocol, Constraint $constraint)
  {
      if(!$constraint instanceof ReservationConstraint) {
          throw new UnexpectedTypeException($constraint, ReservationConstraint::class);
      }

      if ($protocol instanceof RpgReservation) {
          if(!$this->validateRpg($protocol)) {
              $this->context->buildViolation($constraint->message)
                  ->atPath("email")
                  ->setParameter('{{activity}}', $protocol->getRpgTable()->getRpgActivity()->getName())
                  ->setParameter('{{email}}', $protocol->getEmail())
                  ->addViolation();
          };

          return;
      }

      if ($protocol instanceof EntertainmentReservation) {
          if(!$this->validateEntertainement($protocol)) {
            $this->context->buildViolation($constraint->message)
                ->atPath("email")
                ->setParameter('{{activity}}', $protocol->getEntertainmentSchedule()->getEntertainment()->getName())
                ->setParameter('{{email}}', $protocol->getEmail())
                ->addViolation();
          };

          return;
      }

      throw new UnexpectedValueException($protocol, 'rpgReservation | entertainementReservation');
  }

  private function validateRpg(RpgReservation $rpgReservation)
  {
      $rpgTable = $rpgReservation->getRpgTable();

      if ($rpgTable) {
          $email = $rpgReservation->getEmail();
          foreach ($rpgTable->getActivityReservations() as $reservation) {
              if (($reservation !== $rpgReservation) && ($reservation->getEmail() === $email)) {
                  return false;
              }
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
}