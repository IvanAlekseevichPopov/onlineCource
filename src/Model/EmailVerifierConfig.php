<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Mime\Address;

class EmailVerifierConfig
{
    private Address $address;

    private string $confirmationTemplate;

    private string $welcomeTemplate;

    private string $confirmationSubject;

    private string $welcomeSubject;

    private string $expires;

    private string $confirmationRoute;

    public function __construct(Address $address, string $confirmationTemplate, string $welcomeTemplate, string $confirmationSubject, string $welcomeSubject, string $expires, string $confirmationRoute)
    {
        $this->address = $address;
        $this->confirmationTemplate = $confirmationTemplate;
        $this->welcomeTemplate = $welcomeTemplate;
        $this->confirmationSubject = $confirmationSubject;
        $this->welcomeSubject = $welcomeSubject;
        $this->expires = $expires;
        $this->confirmationRoute = $confirmationRoute;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getConfirmationTemplate(): string
    {
        return $this->confirmationTemplate;
    }

    public function getWelcomeTemplate(): string
    {
        return $this->welcomeTemplate;
    }

    public function getConfirmationSubject(): string
    {
        return $this->confirmationSubject;
    }

    public function getWelcomeSubject(): string
    {
        return $this->welcomeSubject;
    }

    public function getExpires(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->expires);
    }

    public function getConfirmationRoute(): string
    {
        return $this->confirmationRoute;
    }
}
