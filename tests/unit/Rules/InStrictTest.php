<?php

namespace StellarWP\Validation\Tests\Unit\Rules;

use InvalidArgumentException;
use StellarWP\Validation\Rules\InStrict;
use StellarWP\Validation\Tests\TestCase;

class InStrictTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldAllowStrictlyEqualValuesInArray()
    {
        $rule = new InStrict('foo', 1);

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, 1);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldFailStrictlyUnequalValues()
    {
        $rule = new InStrict('foo', 1);

        $this->assertValidationRuleFailed($rule, '1');
        $this->assertValidationRuleFailed($rule, 'bar');
        $this->assertValidationRuleFailed($rule, 2);
        $this->assertValidationRuleFailed($rule, false);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldCreateRuleFromCommaDelimitedList()
    {
        $rule = InStrict::fromString('foo,1');

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, '1');
        $this->assertValidationRuleFailed($rule, 1);
        $this->assertValidationRuleFailed($rule, 'qux');
    }

    /**
     * @since 1.2.0
     */
    public function testFromStringShouldRequireValues()
    {
        $this->expectException(InvalidArgumentException::class);

        InStrict::fromString('');
    }

    /**
     * @since 1.2.0
     */
    public function testShouldThrowInvalidExceptionWithoutValues()
    {
        $this->expectException(InvalidArgumentException::class);

        new InStrict();
    }

    /**
     * @since 1.2.0
     */
    public function testSerializeOptionShouldRetunValuesArray()
    {
        $rule = new InStrict('foo', 'bar', 'baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $rule->serializeOption());
    }
}
