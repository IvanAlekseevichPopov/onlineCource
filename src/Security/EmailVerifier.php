<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ConfirmationToken;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private const CONFIRM_ROUTE = 'api_user_registration_confirm'; //TODO env parameters

    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    //TODO divide on two services MAILER and confirmer
    public function sendEmailConfirmation(ConfirmationToken $token): void
    {

        $email = (new TemplatedEmail())
            ->from(new Address('robot@onlineCoure-domain.com', 'onlineCource robot')) //TODO from envs
            ->to($token->getUser()->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('mail/registration_confirm.html.twig') //TODO from params
            ->context([
                'token' => $token,
                'expiresAt' => new \DateTimeImmutable('+ 3 days') //TODO from config
            ]);

        $this->mailer->send($email);
    }


    /**
     * @param ConfirmationToken $token
     */
    public function handleEmailConfirmation(UserInterface $user): void
    {
        //TODO send email with greeting
    }
}
