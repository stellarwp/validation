<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * Mark the value as optional unless the conditions pass
 *
 * @unreleased
 *
 * @see Optional
 */
class OptionalUnless extends ConditionalRule
{
    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'optionalIf';
    }

    /**
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (($value === '' || $value === null) && $this->conditions->fails($values)) {
            return new SkipValidationRules();
        }
    }
}
