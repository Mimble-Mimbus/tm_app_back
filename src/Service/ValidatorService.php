<?php

namespace App\Service;

use App\Exception\ConstraintException;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ValidatorService  {
    public function __construct(
        public RequestStack $requestStack,
        private ValidatorInterface $validator
    ) {}

    public function validate (object $data) {
        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            if($this->requestStack->getMainRequest()) {
                throw new ConstraintException($errors, get_class($data));
            } else {
                throw new Exception('violation of constraints for entity :'. get_class($data));
            }
        }
    }
}
