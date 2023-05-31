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

            // values that fail
            [false, false],
            [0, false],
            ['0', false],
            ['false', false],
            ['no', false],
            ['off', false],
            ['abc', false],
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
