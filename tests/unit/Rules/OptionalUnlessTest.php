<?php

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Rules\OptionalUnless;
use StellarWP\Validation\Tests\TestCase;

class OptionalUnlessTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldSkipWhenConditionsFailAndValueIsNull()
    {
        $mockConditionSet = $this->generateFailingConditionSet();

        $optional = new OptionalUnless($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, null);
    }

    /**
     * @unreleased
     */
    public function testShouldSkipWhenConditionsFailAndValueIsEmptyString()
    {
        $mockConditionSet = $this->generateFailingConditionSet();

        $optional = new OptionalUnless($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($optional, SkipValidationRules::class, '');
    }

    /**
     * @unreleased
     */
    public function testShouldNotSkipWhenConditionsFailAndValueIsNotEmpty()
    {
        $mockConditionSet = $this->generateFailingConditionSet();

        $optional = new OptionalUnless($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class, 'nope');
    }

    /**
     * @unreleased
     */
    public function testShouldNotSkipWhenConditionsPass()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(false);

        $optional = new OptionalUnless($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($optional, SkipValidationRules::class);
    }

    /**
     * @unreleased
     */
    private function generateFailingConditionSet()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(true);

        return $mockConditionSet;
    }
}
