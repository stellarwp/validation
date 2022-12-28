<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * @unreleased
 */
class Email implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'email';
    }

    /**
     * @inheritDoc
     *
     * @unreleased
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
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!is_string($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail(sprintf(__('%s is not a valid email address', 'give'), '{field}'));
        }
    }
}
