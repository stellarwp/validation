<?php

namespace StellarWP\Validation\Tests\Unit\Rules;

use InvalidArgumentException;
use StellarWP\Validation\Rules\InStrict;
use StellarWP\Validation\Tests\TestCase;

class InStrictTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldAllowStrictlyEqualValuesInArray()
    {
        $rule = new InStrict('foo', 1);

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, 1);
    }

    /**
     * @unreleased
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
     * @unreleased
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
     * @unreleased
     */
    public function testFromStringShouldRequireValues()
    {
        $this->expectException(InvalidArgumentException::class);

        InStrict::fromString('');
    }

    /**
     * @unreleased
     */
    public function testShouldThrowInvalidExceptionWithoutValues()
    {
        $this->expectException(InvalidArgumentException::class);

        new InStrict();
    }

    /**
     * @unreleased
     */
    public function testSerializeOptionShouldRetunValuesArray()
    {
        $rule = new InStrict('foo', 'bar', 'baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $rule->serializeOption());
    }
}
