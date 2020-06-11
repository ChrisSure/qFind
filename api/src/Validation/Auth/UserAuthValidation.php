<?php

namespace App\Validation\Auth;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * Class UserAuthValidation
 * @package App\Validation\Auth
 */
class UserAuthValidation
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
                'password' =>
                    [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2])
                    ],
                'type' =>
                    [
                        new Assert\NotBlank()
                    ]

            ]
        );
        return $validator->validate($data, $constraint);
    }
}