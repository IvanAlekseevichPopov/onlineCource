<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ConfirmationToken;
use App\Entity\User;
use App\Model\Request\RegistrationConfirmRequestInterface;
use App\Model\Request\RegistrationRequestInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegisterer
{
    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    private EmailVerifier $emailVerifier;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, EmailVerifier $emailVerifier)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailVerifier = $emailVerifier;
    }

    public function register(RegistrationRequestInterface $request): UserInterface
    {
        $user = new User();

        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->getPassword()));
        $user->setEmail($request->getEmail());

        $token = new ConfirmationToken($user);

        $this->entityManager->persist($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation($token);

        return $user;
    }

    public function confirmRegistration(ConfirmationToken $token): User
    {
        if($token->getCreatedAt()->modify('+ 3 days') > new \DateTimeImmutable('')) {
            // TODO exception
        }

        $user = $token->getUser();
        $this->emailVerifier->handleEmailConfirmation($user);
        //TODO send greeting email

        return $user;
    }
}
