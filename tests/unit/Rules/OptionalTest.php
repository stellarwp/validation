<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\Optional;
use StellarWP\Validation\Tests\TestCase;

class OptionalTest extends TestCase
{
    /**
     * @since 1.1.0
     */
    public function testNullableValidation()
    {
        $rule = new Optional();

        // Passes when value is null and skips remaining tests
        self::assertValidationRulePassed($rule, null);
        self::assertValidationRuleDoesReturnCommandInstance($rule, SkipValidationRules::class, null);

        // Passes when value is empty string and skips remaining tests
        self::assertValidationRulePassed($rule, '');
        self::assertValidationRuleDoesReturnCommandInstance($rule, SkipValidationRules::class, '');

        // Passes on any other value but does not skip remaining tests
        self::assertValidationRulePassed($rule, 'bar');
        self::assertValidationRuleDoesNotReturnCommandInstance($rule, SkipValidationRules::class, 'bar');
    }
}
