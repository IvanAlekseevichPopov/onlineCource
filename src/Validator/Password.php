<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class Password extends Constraint
{
    public const EMPTY = 'Password must not be empty';
    public const TOO_SHORT_MESSAGE = 'This value is too short. It should have {{ length }} characters or more.';
    public const NO_DIGIT = 'Password must contain at least one digit';
    public const NO_CAPITAL = 'Password must contain at least one capital letter';
    public const NO_LOWER_CASE = 'Password must contain at least one lower case character';
}
