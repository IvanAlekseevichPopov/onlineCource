<?php

declare(strict_types=1);

namespace App\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueDto extends Constraint
{
    public $message = 'This value is already used.';

    /**
     * @var string|null
     */
    public $em;

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var array
     */
    public $fields;

    /**
     * @var string|null
     */
    public $errorPath;

    /**
     * @var string|null
     */
    public $entityField;

    /**
     * @var array|null
     */
    public $idFields;

    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!is_array($this->fields)) {
            throw new UnexpectedTypeException($this->fields, 'array');
        }
        if (!is_string($this->entityClass)) {
            throw new UnexpectedTypeException($this->entityClass, 'string');
        }
        if (null !== $this->errorPath && !is_string($this->errorPath)) {
            throw new UnexpectedTypeException($this->errorPath, 'string or null');
        }
        if (count($this->fields) < 1) {
            throw new ConstraintDefinitionException('Please specify at least one field to check');
        }
        if (null !== $this->entityField && null !== $this->idFields) {
            throw new ConstraintDefinitionException('Cannot define both entityField and idFields');
        }
        if (null !== $this->entityField && !is_string($this->entityField)) {
            throw new UnexpectedTypeException($this->entityField, 'string or null');
        }
        if (null !== $this->idFields && !is_array($this->idFields)) {
            throw new UnexpectedTypeException($this->fields, 'array or null');
        }
        if (null !== $this->idFields && count($this->idFields) < 1) {
            throw new ConstraintDefinitionException('Please specify at least one id field to check');
        }
    }

    public function getRequiredOptions()
    {
        return ['entityClass', 'fields'];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
