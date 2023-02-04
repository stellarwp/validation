<?php

declare(strict_types=1);

namespace StellarWP\Validation\Conditions;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use StellarWP\Validation\Config;

class ConditionsArray implements ArrayAccess, IteratorAggregate, JsonSerializable
{
    /**
     * @var list<Condition>
     */
    private $conditions;

    /**
     * @unreleased
     */
    public function __construct(Condition ...$conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @unreleased
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @unreleased
     *
     * @param array<string, mixed> $values
     */
    public function passes(array $values): bool
    {
        return array_reduce(
            $this->conditions,
            static function (bool $passes, Condition $condition) use ($values) {
                return $condition->boolean === 'and'
                    ? $passes && $condition->passes($values)
                    : $passes || $condition->passes($values);
            },
            true
        );
    }

    /**
     * @unreleased
     *
     * @param array<string, mixed> $values
     */
    public function fails(array $values): bool
    {
        return !$this->passes($values);
    }

    /**
     * @unreleased
     *
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->conditions[$offset]);
    }

    /**
     * @unreleased
     *
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->conditions[$offset];
    }

    /**
     * @unreleased
     *
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Condition) {
            Config::throwInvalidArgumentException('ConditionsArray can only contain Condition objects');
        }
    }

    /**
     * @unreleased
     *
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->conditions[$offset]);

        // reset the array keys back to a list
        $this->conditions = array_values($this->conditions);
    }

    /**
     * @unreleased
     *
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->conditions;
    }
}
