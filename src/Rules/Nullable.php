<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * This rule skips further validation if the field is null. It is similar to Optional, but the only allowed value is
 * null.
 *
 * @since 1.1.0
 */
class Nullable implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @since 1.1.0
     */
    public static function id(): string
    {
        return 'nullable';
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
        if ($value === null) {
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
