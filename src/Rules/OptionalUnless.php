<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * Mark the value as optional unless the conditions pass
 *
 * @since 1.2.0
 *
 * @see Optional
 */
class OptionalUnless extends ConditionalRule
{
    /**
     * @since 1.2.2 correct id
     * @since 1.2.0
     */
    public static function id(): string
    {
        return 'optionalUnless';
    }

    /**
     * @since 1.2.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (($value === '' || $value === null) && $this->conditions->fails($values)) {
            return new SkipValidationRules();
        }
    }
}
