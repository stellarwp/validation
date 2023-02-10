<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * Mark the value as optional if the conditions pass
 *
 * @unreleased
 *
 * @see Optional
 */
class OptionalIf extends ConditionalRule
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
        if (($value === '' || $value === null) && $this->conditions->passes($values)) {
            return new SkipValidationRules();
        }
    }
}
