<?php

namespace App\Service\Email;

use App\Entity\User\User;
use Twig\Environment;

class AuthMailService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Send check registration
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    public function sendCheckRegistration(User $user, $token): void
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('admin@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/auth/confirm.html.twig',
                    ['id' => $user->getId(),'token' => $token]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    /**
     * Send forget password
     *
     * @param User $user
     * @param string $token
     * @return void
     */
    public function sendForgetPassword(User $user, $token): void
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('admin@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'emails/auth/forget.html.twig',
                    ['id' => $user->getId(),'token' => $token]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}