<?php

declare(strict_types=1);

namespace StellarWP\Validation\Contracts;

use Closure;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Commands\SkipValidationRules;

interface ValidationRule
{
    /**
     * The unique id of the validation rule.
     *
     * @since 1.0.0
     */
    public static function id(): string;

    /**
     * Creates a new instance of the validation rule from a string form. The string may be something like:
     * "required" or "max:255".
     *
     * If a value is provided after the colon, it will be the options' parameter.
     *
     * @since 1.0.0
     */
    public static function fromString(string $options = null): ValidationRule;

    /**
     * The invokable method used to validate the value. If the value is invalid, the fail callback should be invoked
     * with the error message. Use {field} to reference the field name in the error message.
     *
     * @since 1.2.0 add ExcludeValue return option
     * @since 1.0.0
     *
     * @return void|ExcludeValue|SkipValidationRules
     */
    public function __invoke($value, Closure $fail, string $key, array $values);
}
