<?php

namespace unit\Rules;

use InvalidArgumentException;
use StellarWP\Validation\Rules\In;
use StellarWP\Validation\Tests\TestCase;

class InTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldAllowEquivalentValuesInArray()
    {
        $rule = new In('foo', 1);

        $this->assertValidationRulePassed($rule, 'foo');
        $this->assertValidationRulePassed($rule, 1);
        $this->assertValidationRulePassed($rule, '1');
    }

    /**
     * @since 1.2.0
     */
    public function testShouldFailValuesNotInArray()
    {
        $rule = new In('foo', 1);

        $this->assertValidationRuleFailed($rule, 'bar');
        $this->assertValidationRuleFailed($rule, 2);
        $this->assertValidationRuleFailed($rule, false);
    }

    /**
     * @since 1.2.0
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
     * @since 1.2.0
     */
    public function testFromStringShouldRequireValues()
    {
        $this->expectException(InvalidArgumentException::class);

        In::fromString('');
    }

    /**
     * @since 1.2.0
     */
    public function testShouldThrowInvalidExceptionWithoutValues()
    {
        $this->expectException(InvalidArgumentException::class);

        new In();
    }

    /**
     * @since 1.2.0
     */
    public function testSerializeOptionShouldRetunValuesArray()
    {
        $rule = new In('foo', 'bar', 'baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $rule->serializeOption());
    }
}
