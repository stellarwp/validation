<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * Applying this rule will prevent all further validations and exclude the value from the validated dataset.
 *
 * @unreleased
 */
class Exclude implements ValidationRule
{
    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'exclude';
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
     *
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values): ExcludeValue
    {
        return new ExcludeValue();
    }
}
