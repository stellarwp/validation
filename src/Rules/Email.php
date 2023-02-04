<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * @since 1.0.0
 */
class Email implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function id(): string
    {
        return 'email';
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function serializeOption()
    {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!is_string($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail(sprintf(__('%s is not a valid email address', '%TEXTDOMAIN%'), '{field}'));
        }
    }
}
