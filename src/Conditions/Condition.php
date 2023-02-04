<?php

namespace StellarWP\Validation\Conditions;

use JsonSerializable;

/**
 * @since 2.13.0
 */
abstract class Condition implements JsonSerializable
{
    const BOOLEANS = ['and', 'or'];

    /**
     * @var string either "and" or "or"
     */
    public $boolean;

    /**
     * @since 2.13.0
     *
     * {@inheritDoc}
     */
    abstract public function jsonSerialize(): array;

    /**
     * @param array<string, mixed> $testValues
     */
    abstract public function passes(array $testValues): bool;
}
