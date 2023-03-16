<?php

declare(strict_types=1);

namespace unit\Rules\Abstracts;

use Closure;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\Validation\Rules\Abstracts\ConditionalRule;
use StellarWP\Validation\Tests\TestCase;

/**
 * @since 1.2.0
 */
class ConditionalRuleTest extends TestCase
{
    /**
     * @since 1.2.0
     */
    public function testShouldAcceptConditionSetInConstructor()
    {
        $rule = new MockConditionalRule($this->createMock(ConditionSet::class));

        $this->assertInstanceOf(ConditionalRule::class, $rule);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldAcceptConditionsArrayInConstructor()
    {
        $rule = new MockConditionalRule([$this->createMock(Condition::class)]);

        $this->assertInstanceOf(ConditionalRule::class, $rule);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldCreateConditionSetFromOptionsString()
    {
        $rule = MockConditionalRule::fromString('field1,value1;field2,value2');

        $this->assertInstanceOf(MockConditionalRule::class, $rule);
    }

    /**
     * @since 1.2.0
     */
    public function testShouldReturnSerializedConditions()
    {
        $conditionSet = $this->createMock(ConditionSet::class);
        $conditionSet->method('jsonSerialize')->willReturn([]);

        $rule = new MockConditionalRule($conditionSet);

        self::assertSame([], $rule->serializeOption());
    }
}

/**
 * @since 1.2.0
 */
class MockConditionalRule extends ConditionalRule
{
    public static function id(): string
    {
        return 'mock';
    }

    public function __invoke($value, Closure $fail, string $key, array $values)
    {
    }
}
