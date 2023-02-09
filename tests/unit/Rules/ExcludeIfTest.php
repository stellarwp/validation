<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\FieldConditions\FieldCondition;
use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Rules\ExcludeIf;
use StellarWP\Validation\Tests\TestCase;

class ExcludeIfTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldReturnExcludedValueWhenConditionPasses()
    {
        $exclude = new ExcludeIf([new FieldCondition('age', '>=', 18)]);

        $values = ['age' => 18, 'name' => 'John Doe'];

        self::assertInstanceOf(
            ExcludeValue::class,
            $exclude(
                null,
                static function () {
                },
                'name',
                $values
            )
        );
    }

    public function testShouldNotReturnExcludeValueWhenConditionsFail()
    {
        $exclude = new ExcludeIf([new FieldCondition('age', '>=', 18)]);

        $values = ['age' => 17, 'name' => 'John Doe'];

        self::assertNotInstanceOf(
            ExcludeValue::class,
            $exclude(
                null,
                static function () {
                },
                'name',
                $values
            )
        );
    }
}
