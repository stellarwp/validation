<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Rules\Currency;
use StellarWP\Validation\Tests\TestCase;
class CurrencyTest extends TestCase
{
    /**
     * @since 1.1.0
     * @dataProvider currencyProvider
     */
    public function testCurrencyValidations($currency, $shouldPass)
    {
        $rule = new Currency();

        if ( $shouldPass ) {
            self::assertValidationRulePassed($rule, $currency);
        } else {
            self::assertValidationRuleFailed($rule, $currency);
        }
    }

    /**
     * @since 1.1.0
     */
    public function currencyProvider(): array
    {
        return [
            // normal
            ['USD', true],
            ['CAD', true],

            // should not be case-sensitive
            ['jpy', true],
            ['EuR', true],

            // should fail
            ['US', false],
            ['USDD', false],
            ['US D', false],
            ['US-D', false],
            ['ABC', false],
            ['123', false],
        ];
    }
}
