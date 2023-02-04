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
 * @unreleased
 */
class Nullable implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'nullable';
    }

    /**
     * @unreleased
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @unreleased
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
     * @unreleased
     */
    public function serializeOption()
    {
        // TODO: Implement serializeOption() method.
    }
}
