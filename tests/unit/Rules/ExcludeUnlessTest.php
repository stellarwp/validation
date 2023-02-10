<?php

declare(strict_types=1);

namespace unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Rules\ExcludeUnless;
use StellarWP\Validation\Tests\TestCase;

class ExcludeUnlessTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldReturnExcludedValueWhenConditionFails()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(true);

        $exclude = new ExcludeUnless($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($exclude, ExcludeValue::class);
    }

    /**
     * @unreleased
     */
    public function testShouldNotReturnExcludeValueWhenConditionsPass()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('fails')->willReturn(false);

        $exclude = new ExcludeUnless($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($exclude, ExcludeValue::class);
    }
}
