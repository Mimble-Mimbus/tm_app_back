<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ReservationConstraint extends Constraint
{
    public $message = "reservation with email: {{email}} for animation: {{activity}} already exist";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
}