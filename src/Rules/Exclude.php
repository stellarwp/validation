<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * Applying this rule will prevent all further validations and exclude the value from the validated dataset.
 *
 * @since 1.2.0
 */
class Exclude implements ValidationRule
{
    /**
     * @inheritDoc
     *
     * @since 1.2.0
     */
    public static function id(): string
    {
        return 'exclude';
    }

    /**
     * @inheritDoc
     *
     * @since 1.2.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @inheritDoc
     *
     * @since 1.2.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values): ExcludeValue
    {
        return new ExcludeValue();
    }
}
