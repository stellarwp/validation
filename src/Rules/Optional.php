<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * This rule marks a field as optional and skips further validation if the rule is either null or an empty string.
 *
 * @since 1.1.0
 */
class Optional implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @since 1.1.0
     */
    public static function id(): string
    {
        return 'optional';
    }

    /**
     * @since 1.1.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @since 1.1.0
     *
     * @return SkipValidationRules|void
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if ($value === null || $value === '') {
            return new SkipValidationRules();
        }
    }

    /**
     * @since 1.1.0
     */
    public function serializeOption()
    {
        return null;
    }
}
