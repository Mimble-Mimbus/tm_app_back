<?php

namespace App\Service;

use App\Exception\ConstraintException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService  {
    private ValidatorInterface $validator;
    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    public function validate (object $data) {
        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            throw new ConstraintException($errors, get_class($data));
        }
    }
}
