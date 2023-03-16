<?php

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\NullableIf;
use StellarWP\Validation\Tests\TestCase;

class NullableIfTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldReturnSkipValidationRulesWhenConditionPasses()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(true);

        $nullable = new NullableIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($nullable, SkipValidationRules::class);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldNotReturnSkipValidationRulesWhenConditionFails()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(false);

        $nullable = new NullableIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($nullable, SkipValidationRules::class);
    }
}
