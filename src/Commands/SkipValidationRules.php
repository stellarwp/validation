<?php

declare(strict_types=1);

namespace StellarWP\Validation\Commands;

/**
 * Returning this command from ValidationRule::__invoke() tells the Validator to skip all subsequent rules.
 *
 * @unreleased
 */
class SkipValidationRules
{
}
