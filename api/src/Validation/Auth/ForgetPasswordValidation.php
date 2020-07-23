<?php

namespace App\Validation\Auth;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Class ForgetPasswordValidation
 * @package App\Validation\Auth
 */
class ForgetPasswordValidation
{
    /**
     * Validor for login
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validate(array $data): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(
            [
                'email' =>
                    [
                        new Assert\NotBlank(),
                        new Assert\Email()
                    ],
            ]
        );
        return $validator->validate($data, $constraint);
    }
}