<?php

declare(strict_types=1);

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
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

    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    //TODO divide on two services MAILER and confirmer
    public function sendEmailConfirmation(UserInterface $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            self::CONFIRM_ROUTE,
            $user->getId()->toString(),
            $user->getEmail()
        );

        $email = (new TemplatedEmail())
            ->from(new Address('robot@onlineCoure-domain.com', 'onlineCource robot')) //TODO from envs
            ->to($user->getEmail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('mail/registration_confirm.html.twig') //TODO from params
            ->context([
                'signedUrl' => $signatureComponents->getSignedUrl(),
                'expiresAt' => $signatureComponents->getExpiresAt(),
            ]);

        $this->mailer->send($email);
    }
//
//
//    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
//    {
//        $signatureComponents = $this->verifyEmailHelper->generateSignature(
//            $verifyEmailRouteName,
//            $user->getId(),
//            $user->getEmail()
//        );
//
//        $context = $email->getContext();
//        $context['signedUrl'] = $signatureComponents->getSignedUrl();
//        $context['expiresAt'] = $signatureComponents->getExpiresAt();
//
//        $email->context($context);
//
//        $this->mailer->send($email);
//    }
//
//    /**
//     * @throws VerifyEmailExceptionInterface
//     */
//    public function handleEmailConfirmation(Request $request, UserInterface $user): void
//    {
//        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
//
//        $user->setIsVerified(true);
//
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//    }
}
