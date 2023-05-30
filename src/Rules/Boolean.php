<?php

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Contracts\Sanitizer;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

class Boolean implements ValidationRule, ValidatesOnFrontEnd, Sanitizer
{
    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'boolean';
    }

    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            $fail(sprintf(__('%s must be an boolean', '%TEXTDOMAIN%'), '{field}'));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public function serializeOption()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @unreleased
     */
    public function sanitize($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
