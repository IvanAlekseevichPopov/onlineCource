<?php

declare(strict_types=1);

namespace App\Model\Request;

interface RegistrationRequestInterface
{
    public function getPassword(): ?string;

    public function getEmail(): ?string;
}
