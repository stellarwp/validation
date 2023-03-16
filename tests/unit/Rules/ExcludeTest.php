<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Rules;

use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Rules\Exclude;
use StellarWP\Validation\Tests\TestCase;

class ExcludeTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldReturnExcludedValueWhenUsed()
    {
        $exclude = new Exclude();

        self::assertInstanceOf(ExcludeValue::class, $exclude(null, function() {}, 'foo', []));
    }
}
