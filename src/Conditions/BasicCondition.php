<?php

namespace StellarWP\Validation\Conditions;

use InvalidArgumentException;
use StellarWP\Validation\Config;

/**
 * @since 2.13.0
 */
class BasicCondition extends Condition
{
    const OPERATORS = ['=', '!=', '>', '>=', '<', '<=', 'contains', 'not_contains'];

    /** @var string */
    const TYPE = 'basic';

    /** @var string */
    public $field;

    /** @var mixed */
    public $value;

    /** @var string */
    public $operator;

    /**
     * Create a new BasicCondition.
     *
     * @since 2.13.0
     *
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @param string $boolean
     */
    public function __construct(string $field, string $operator, $value, string $boolean = 'and')
    {
        if ($this->invalidOperator($operator)) {
            throw Config::throwInvalidArgumentException(
                "Invalid operator: $operator. Must be one of: " . implode(', ', self::OPERATORS)
            );
        }

        if ($this->invalidBoolean($boolean)) {
            throw Config::throwInvalidArgumentException(
                "Invalid boolean: $boolean. Must be one of: " . implode(', ', self::BOOLEANS)
            );
        }

        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $this->boolean = $boolean;
    }

    /**
     * Check if the provided operator is invalid.
     *
     * @since 2.13.0
     */
    protected function invalidOperator(string $operator): bool
    {
        return !in_array($operator, static::OPERATORS, true);
    }

    /**
     * Check if the provided boolean is invalid.
     *
     * @since 2.13.0
     */
    protected function invalidBoolean(string $boolean): bool
    {
        return !in_array($boolean, static::BOOLEANS, true);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::TYPE,
            'field' => $this->field,
            'value' => $this->value,
            'operator' => $this->operator,
            'boolean' => $this->boolean,
        ];
    }

    public function passes(array $testValues): bool
    {
        if ( !isset($testValues[$this->field])) {
            throw new InvalidArgumentException("Field {$this->field} not found in test values.");
        }

        $testValue = $testValues[$this->field];

        switch ($this->operator) {
            case '=':
                return $testValue === $this->value;
            case '!=':
                return $testValue !== $this->value;
            case '>':
                return $testValue > $this->value;
            case '>=':
                return $testValue >= $this->value;
            case '<':
                return $testValue < $this->value;
            case '<=':
                return $testValue <= $this->value;
            case 'contains':
                return str_contains($testValue, $this->value);
            case 'not_contains':
                return !str_contains($testValue, $this->value);
        }
    }
}
