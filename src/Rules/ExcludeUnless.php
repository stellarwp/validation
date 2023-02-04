<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;

/**
 * Exclude a field unless the given conditions are met.
 *
 * @see Exclude
 *
 * @unreleased
 */
class ExcludeUnless extends ConditionalRule
{
    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'excludeUnless';
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!$this->conditions->passes($values)) {
            return new ExcludeValue();
        }
    }
}