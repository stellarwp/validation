<?php

namespace StellarWP\Validation\Rules;

use Closure;

class InStrict extends In
{
    /**
     * @since 1.2.0
     */
    public static function id(): string
    {
        return 'inStrict';
    }

    /**
     * @since 1.2.0
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!in_array($value, $this->acceptedValues, true)) {
            $fail(sprintf(__('%s must be one of %s', '%TEXTDOMAIN%'), '{field}', implode(', ', $this->acceptedValues)));
        }
    }
}
