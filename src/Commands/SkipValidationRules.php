<?php

declare(strict_types=1);

namespace StellarWP\Validation\Commands;

/**
 * Returning this command from ValidationRule::__invoke() tells the Validator to skip all subsequent rules.
 *
 * @since 1.1.0
 */
class SkipValidationRules
{
}
