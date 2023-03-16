<?php

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\NullableUnless;
use StellarWP\Validation\Tests\TestCase;

class NullableUnlessTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldReturnSkipValidationRulesWhenConditionFails()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(true);

        $nullable = new NullableUnless($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($nullable, SkipValidationRules::class);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldNotReturnSkipValidationRulesWhenConditionsPass()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(false);

        $nullable = new NullableUnless($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($nullable, SkipValidationRules::class);
    }
}
