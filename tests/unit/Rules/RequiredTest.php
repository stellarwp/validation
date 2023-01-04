<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Rules\Required;
use StellarWP\Validation\Tests\TestCase;

class RequiredTest extends TestCase
{
    public function testRuleValidation()
    {
        $rule = new Required();

        // Value must be present in the array of values and not empty
        self::assertValidationRulePassed($rule, 'hi', 'foo', ['foo' => 'hi']);

        // Value fails when present but empty
        self::assertValidationRuleFailed($rule, '', 'foo', ['foo' => '']);

        // Value fails when null
        self::assertValidationRuleFailed($rule, null, 'foo', ['foo' => null]);

        // Value fails when not present
        self::assertValidationRuleFailed($rule, '', 'foo', []);
    }
}
