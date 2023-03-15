<?php

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\OptionalIf;
use StellarWP\Validation\Tests\TestCase;

class OptionalIfTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldReturnSkipValidationRulesWhenConditionPasses()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, null);
    }

    /**
     * @unreleased
     */
    public function testShouldSkipWhenConditionsPassAndValueIsEmptyString()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, '');
    }

    /**
     * @unreleased
     */
    public function testShouldNotSkipWhenConditionsPassAndValueIsNotEmpty()
    {
        $mockConditionSet = $this->generatePassingConditionSet();

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class, 'nope');
    }

    /**
     * @unreleased
     */
    public function testShouldNotReturnSkipValidationRulesWhenConditionFails()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(false);

        $optional = new OptionalIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class);
    }

    /**
     * @unreleased
     */
    private function generatePassingConditionSet()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(true);

        return $mockConditionSet;
    }
}
