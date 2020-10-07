<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class UniqueEntityProperty extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is already used.';

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $repositoryMethod = 'findBy';

    /**
     * @var bool
     */
    public $lowercase = false;

    /**
     * @var string|null
     */
    public $currentEntityExpression;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(): array
    {
        return ['entityClass', 'field'];
    }
}
