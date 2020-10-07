<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ConfirmationToken;
use App\Entity\User;
use App\Exception\Registration\RegistrationConfirmOutdatedException;
use App\Model\Request\RegistrationRequestInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegisterer
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EmailVerifier $emailVerifier;
    private string $confirmTokenLifeTime;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, EmailVerifier $emailVerifier, string $confirmTokenLifeTime)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->emailVerifier = $emailVerifier;
        $this->confirmTokenLifeTime = $confirmTokenLifeTime;
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
        if ($token->getCreatedAt()->modify($this->confirmTokenLifeTime) < new \DateTimeImmutable()) {
            throw new RegistrationConfirmOutdatedException();
        }

        $user = $token->getUser();
        $this->emailVerifier->handleEmailConfirmation($user);

        return $user;
    }
}
