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
 * @unreleased
 */
class Optional implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'optional';
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
        if ($value === null || $value === '') {
            return new SkipValidationRules();
        }
    }

    /**
     * @unreleased
     */
    public function serializeOption()
    {
        return null;
    }
}
