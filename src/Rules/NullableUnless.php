<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * The value is nullable unless the conditions pass.
 *
 * @unreleased
 */
class NullableUnless extends ConditionalRule
{
    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'nullableUnless';
    }

    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if ($value === null && $this->conditions->fails($values)) {
            return new SkipValidationRules();
        }
    }
}
