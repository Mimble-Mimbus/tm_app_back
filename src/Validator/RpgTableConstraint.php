<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class RpgTableConstraint extends Constraint
{
    public $message = "le créneau n'est pas disponible";
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
}