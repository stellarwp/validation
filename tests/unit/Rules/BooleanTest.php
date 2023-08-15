<?php

namespace unit\Rules;

use StellarWP\Validation\Rules\Boolean;
use StellarWP\Validation\Tests\TestCase;

class BooleanTest extends TestCase
{
    /**
     * @since 1.4.0
     *
     * @dataProvider booleansProvider
     */
    public function testRuleValidatesBooleans($value, $pass)
    {
        $rule = new Boolean();

        if ($pass) {
            self::assertValidationRulePassed($rule, $value);
        } else {
            self::assertValidationRuleFailed($rule, $value);
        }
    }

    /**
     * @since 1.4.1 updates tests to pass false-y values
     * @since 1.4.0
     */
    public function booleansProvider(): array
    {
        return [
            // values that pass
            [true, true],
            [1, true],
            ['1', true],
            ['true', true],
            ['yes', true],
            ['on', true],
            [false, true],
            [0, true],
            ['0', true],
            ['false', true],
            ['no', true],
            ['off', true],

            // values that fail
            ['abc', false],
            ['123', false],
        ];
    }

    /**
     * @since 1.4.0
     */
    public function testCastsToBoolean()
    {
        $rule = new Boolean();
        self::assertSame(true, $rule->sanitize('1'));
        self::assertSame(false, $rule->sanitize('0'));
    }
}
