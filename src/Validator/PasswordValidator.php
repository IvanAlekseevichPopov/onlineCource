<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{
    public const MIN_LENGTH = 6;
    public const ONE_CHAR = '/.*[0-9].*/';
    public const ONE_CAPITAL = '/.*[A-Z].*/';
    public const ONE_LOWER = '/.*[a-z].*/';

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            $this->context
                ->buildViolation(Password::EMPTY)
                ->addViolation();

            return;
        }

        if (strlen($value) < self::MIN_LENGTH) {
            $this->context
                ->buildViolation(Password::TOO_SHORT_MESSAGE)
                ->setParameter('{{ length }}', (string) self::MIN_LENGTH)
                ->addViolation();
        }

        if (!preg_match(self::ONE_CHAR, $value)) {
            $this->context
                ->buildViolation(Password::NO_DIGIT)
                ->addViolation();
        }

        if (!preg_match(self::ONE_CAPITAL, $value)) {
            $this->context
                ->buildViolation(Password::NO_CAPITAL)
                ->addViolation();
        }

        if (!preg_match(self::ONE_LOWER, $value)) {
            $this->context
                ->buildViolation(Password::NO_LOWER_CASE)
                ->addViolation();
        }
    }
}
