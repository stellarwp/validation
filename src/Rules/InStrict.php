<?php

namespace StellarWP\Validation\Rules;

use Closure;

class InStrict extends In
{
    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'inStrict';
    }

    /**
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!in_array($value, $this->acceptedValues, true)) {
            $fail(sprintf(__('%s must be one of %s', '%TEXTDOMAIN%'), '{field}', implode(', ', $this->acceptedValues)));
        }
    }
}
