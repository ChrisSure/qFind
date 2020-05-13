<?php

namespace App\Service\Auth;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PasswordHashService
 * @package App\Service\Helpers
 */
class PasswordHashService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Hash password
     * @param User $user
     * @param string $password
     * @return string
     */
    public function hashPassword(User $user, $password): string
    {
        return $this->encoder->encodePassword($user, $password);
    }

    /**
     * Check user password
     * @param string $password
     * @param UserInterface $user
     * @return bool
     */
    public function checkPassword($password, UserInterface $user): bool
    {
        return $this->encoder->isPasswordValid($user, $password);
    }
}