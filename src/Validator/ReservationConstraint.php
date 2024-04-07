<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ReservationConstraint extends Constraint
{
    public $emailMessage = "reservation with email: {{email}} for animation: {{activity}} already exist";
    
    public $phoneNumberMessage = "invalide phone number {{value}}";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
}