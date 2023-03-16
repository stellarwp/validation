<?php

namespace unit\Rules;

use InvalidArgumentException;
use StellarWP\Validation\Rules\In;
use StellarWP\Validation\Tests\TestCase;

class InTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldAllowEquivalentValuesInArray()
    {
        $rule = new In('foo', 1);

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, 1);
        $this->assertValidationRulePassed($rule, '1');
    }

    /**
     * @unreleased
     */
    public function testShouldFailValuesNotInArray()
    {
        $rule = new In('foo', 1);

        $this->assertValidationRuleFailed($rule, 'bar');
        $this->assertValidationRuleFailed($rule, 2);
        $this->assertValidationRuleFailed($rule, false);
    }

    /**
     * @unreleased
     */
    public function testShouldCreateRuleFromCommaDelimitedList()
    {
        $rule = In::fromString('foo,1');

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, 1);
        $this->assertValidationRulePassed($rule, '1');
        $this->assertValidationRuleFailed($rule, 'qux');
    }

    /**
     * @unreleased
     */
    public function testFromStringShouldRequireValues()
    {
        $this->expectException(InvalidArgumentException::class);

        In::fromString('');
    }

    /**
     * @unreleased
     */
    public function testShouldThrowInvalidExceptionWithoutValues()
    {
        $this->expectException(InvalidArgumentException::class);

        new In();
    }

    /**
     * @unreleased
     */
    public function testSerializeOptionShouldRetunValuesArray()
    {
        $rule = new In('foo', 'bar', 'baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $rule->serializeOption());
    }
}
