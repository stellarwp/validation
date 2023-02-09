<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Rules\Numeric;
use StellarWP\Validation\Tests\TestCase;
class NumericTest extends TestCase
{
    /**
     * @since 1.1.0
     */
    public function testShouldPassWhenValueIsNumeric()
    {
        $rule = new Numeric();

        self::assertValidationRulePassed($rule, 1);
        self::assertValidationRulePassed($rule, 100.00);
        self::assertValidationRulePassed($rule, '1');
        self::assertValidationRulePassed($rule, '100.00');
    }

    /**
     * @since 1.1.0
     */
    public function testShouldFailWhenValueIsNotNumeric()
    {
        $rule = new Numeric();

        self::assertValidationRuleFailed($rule, 'abc');
        self::assertValidationRuleFailed($rule, 'abc123');
        self::assertValidationRuleFailed($rule, '123abc');
        self::assertValidationRuleFailed($rule, '123.abc');
        self::assertValidationRuleFailed($rule, '123.345.56');
        self::assertValidationRuleFailed($rule, 'abc.123');
        self::assertValidationRuleFailed($rule, 'abc123.abc');
        self::assertValidationRuleFailed($rule, 'abc.123abc');
        self::assertValidationRuleFailed($rule, 'abc123.abc456');
    }
}
