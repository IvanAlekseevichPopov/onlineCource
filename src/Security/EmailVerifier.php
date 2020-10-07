<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ConfirmationToken;
use App\Entity\User;
use App\Model\EmailVerifierConfig;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailVerifier
{
    private const CONFIRM_ROUTE = 'api_user_registration_confirm'; //TODO env parameters

    private MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private EmailVerifierConfig $config;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $manager, RouterInterface $router, EmailVerifierConfig $config)
    {
        $this->mailer = $mailer;
        $this->entityManager = $manager;
        $this->router = $router;
        $this->config = $config;
    }

    public function sendEmailConfirmation(ConfirmationToken $token): void
    {
        $email = (new TemplatedEmail())
            ->from($this->config->getAddress())
            ->to($token->getUser()->getEmail())
            ->subject($this->config->getConfirmationSubject())
            ->htmlTemplate($this->config->getConfirmationTemplate())
            ->context([
                'confirmationUrl' => $this->router->generate(self::CONFIRM_ROUTE, ['token' => $token->getId()], RouterInterface::ABSOLUTE_URL),
                'expiresAt' => $this->config->getExpires(),
            ]);

        $this->mailer->send($email);
    }

    public function handleEmailConfirmation(User $user): void //TODO userinterface with email
    {
        $email = (new TemplatedEmail())
            ->from($this->config->getAddress())
            ->to($user->getEmail())
            ->subject($this->config->getWelcomeSubject())
            ->htmlTemplate($this->config->getWelcomeTemplate())
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}
