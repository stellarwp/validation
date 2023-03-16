<?php

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\OptionalIf;
use StellarWP\Validation\Tests\TestCase;

class OptionalIfTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldReturnSkipValidationRulesWhenConditionPasses()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, null);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldSkipWhenConditionsPassAndValueIsEmptyString()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, '');
    }

    /**
     * @since 1.2.0
     */
    public function testShouldNotSkipWhenConditionsPassAndValueIsNotEmpty()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class, 'nope');
    }

    /**
     * @since 1.2.0
     */
    public function testShouldNotReturnSkipValidationRulesWhenConditionFails()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(false);

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class);
    }

    /**
     * @since 1.2.0
     */
    private function generatePassingConditionSet()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(true);

        return $mockConditionSet;
    }
}
