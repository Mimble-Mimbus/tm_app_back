<?php

namespace App\Validator;

use App\Entity\RpgTable;
use DateInterval;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class RpgTableConstraintValidator extends ConstraintValidator
{

  public function validate(mixed $protocol, Constraint $constraint): void
  {
      if (!$constraint instanceof RpgTableConstraint) {
          throw new UnexpectedTypeException($constraint, RpgTableConstraint::class);
      }

      if (!($protocol instanceof RpgTable)) {
          throw new UnexpectedValueException($protocol, RpgTable::class);
      }

      if ($this->isAlreadyScheduled($protocol)) {
          $this->context->buildViolation($constraint->message)
              ->atPath('schedule-error')
              ->addViolation();
      }

      
  }

  private function isBetween (int $currentStart, int $currentEnd, int $actualStart, int $actualEnd)
  {
    return (($currentStart >= $actualStart) && ($currentStart <  $actualEnd)) ||
      (($actualStart >= $currentStart) && ($actualStart <= $currentEnd));
  }

  private function isAlreadyScheduled (RpgTable $rpgTable)
  {
    $numberScheduled = 0;
    $rpgZone = $rpgTable->getRpgActivity()->getRpgZone();
    $schedules = [];
    foreach ($rpgZone->getRpgActivities() as $rpgActivity) {
        foreach ($rpgActivity->getActivitySchedules() as $schedule) {
            $schedules[] = $schedule;
            $currentStart = $schedule->getStart();
            $actualDate = $rpgTable->getStart();
            $currentEnd = clone $currentStart;
            date_add($currentEnd, new DateInterval("PT{$rpgActivity->getDuration()}H"));
            $actualEnd = clone $actualDate;
            date_add($actualEnd, new DateInterval("PT{$rpgTable->getDuration()}H"));

            if ($currentStart->format('Y-m-d') !== $actualDate->format('Y-m-d')) {
                continue;
            }
      
            if ($numberScheduled > $rpgZone->getMaxAvailableSeatsPerTable()) {
                return true;
            }
      
            if ($this->isBetween($currentStart->getTimestamp() ,$currentEnd->getTimestamp(), $actualDate->getTimestamp(), $actualEnd->getTimestamp())) {
                $numberScheduled++;
            }
        }
    }

    return false;
  }
}