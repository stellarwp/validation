<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Rules\ExcludeIf;
use StellarWP\Validation\Tests\TestCase;

class ExcludeIfTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldReturnExcludedValueWhenConditionPasses()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(true);

        $exclude = new ExcludeIf($mockConditionSet);

        self::assertValidationRuleDoesReturnCommandInstance($exclude, ExcludeValue::class);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldNotReturnExcludeValueWhenConditionsFail()
    {
        $mockConditionSet = $this->createMock(ConditionSet::class);
        $mockConditionSet->method('passes')->willReturn(false);

        $exclude = new ExcludeIf($mockConditionSet);

        self::assertValidationRuleDoesNotReturnCommandInstance($exclude, ExcludeValue::class);
    }
}
