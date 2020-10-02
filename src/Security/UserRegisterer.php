<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Model\Request\RegistrationRequestInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegisterer
{
    private EntityManager $entityManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    private EmailVerifier $emailVerifier;

    public function register(RegistrationRequestInterface $request): UserInterface
    {
        $user = new User();

        $user->setPassword($this->passwordEncoder->encodePassword($user, $request->getPassword()));
        $user->setEmail($request->getEmail());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation($user);

        return $user;
    }
}
