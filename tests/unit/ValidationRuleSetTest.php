<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit\Framework\Validation;

use InvalidArgumentException;
use StellarWP\Validation\Rules\Required;
use StellarWP\Validation\Rules\Size;
use StellarWP\Validation\Tests\TestCase;
use StellarWP\Validation\ValidationRuleSet;
use StellarWP\Validation\ValidationRulesRegistrar;

/**
 * @covers ValidationRuleSet
 *
 * @since 1.1.0
 */
class ValidationRuleSetTest extends TestCase
{
    /**
     * @since 1.1.0
     */
    public function testRulesCanBePassedAsStrings()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertCount(2, $rules);
    }

    /**
     * @since 1.1.0
     */
    public function testRulesCanBePassedAsInstances()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(new Required(), new Size(5));

        self::assertCount(2, $rules);
    }

    /**
     * @since 1.1.0
     */
    public function testRulesCanBePassedAsClosures()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value, $fail) {
        });

        self::assertCount(1, $rules);
    }

    /**
     * @since 1.3.0
     */
    public function testPrependingARule()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('size:5');
        $rules->prependRule(new Required());

        self::assertCount(2, $rules);
        self::assertInstanceOf(Required::class, $rules->getRule('required'));
        self::assertJsonStringEqualsJsonString(
            json_encode([
                'required' => true,
                'size' => 5,
            ]),
            json_encode($rules)
        );
    }

    /**
     * @since 1.1.0
     */
    public function testCheckingHasRule()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertFalse($rules->hasRule('email'));
    }

    /**
     * @since 1.3.0
     */
    public function testCheckingHasAnyRules()
    {
        // True if it has rules
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertTrue($rules->hasRules());

        // False if it has no rules
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        self::assertFalse($rules->hasRules());
    }

    /**
     * @since 1.1.0
     */
    public function testGettingARule()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertInstanceOf(Required::class, $rules->getRule('required'));
        self::assertInstanceOf(Size::class, $rules->getRule('size'));
        self::assertNull($rules->getRule('email'));
    }

    /**
     * @since 1.1.0
     */
    public function testGettingAllRules()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertCount(2, $rules->getRules());
    }

    /**
     * @since 1.1.0
     */
    public function testForgettingARule()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');
        $rules->removeRuleWithId('required');

        self::assertCount(1, $rules);
        self::assertFalse($rules->hasRule('required'));
    }

    /**
     * @since 1.3.0
     */
    public function testReplacingARule()
    {
        // Replace if rule exists
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertTrue($rules->replaceRule('size', new Size(10)));
        self::assertCount(2, $rules);
        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertEquals(10, $rules->getRule('size')->getSize());

        // Do not replace if rule does not exist
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required');

        self::assertFalse($rules->replaceRule('size', new Size(10)));
        self::assertCount(1, $rules);
    }

    /**
     * @since 1.3.0
     */
    public function testConditionallyReplacingOrAppendingARule()
    {
        // Replace if rule exists
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');


        self::assertTrue($rules->replaceOrAppendRule('size', new Size(10)));
        self::assertCount(2, $rules);
        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertEquals(10, $rules->getRule('size')->getSize());

        // Append if rule does not exist
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required');

        self::assertFalse($rules->replaceOrAppendRule('size', new Size(10)));
        self::assertCount(2, $rules);
        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertEquals(10, $rules->getRule('size')->getSize());
    }

    /**
     * @since 1.3.0
     */
    public function testConditionallyReplacingOrPrependingRules()
    {
        // Replace if rule exists
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::AssertTrue($rules->replaceOrPrependRule('size', new Size(10)));
        self::assertCount(2, $rules);
        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertEquals(10, $rules->getRule('size')->getSize());

        // Prepend if rule does not exist
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required');

        self::assertFalse($rules->replaceOrPrependRule('size', new Size(10)));
        self::assertCount(2, $rules);
        self::assertTrue($rules->hasRule('required'));
        self::assertTrue($rules->hasRule('size'));
        self::assertEquals(10, $rules->getRule('size')->getSize());
    }

    /**
     * @since 1.1.0
     */
    public function testRulesCanBeSerializedToJson()
    {
        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules('required', 'size:5');

        self::assertJsonStringEqualsJsonString(
            json_encode([
                'required' => true,
                'size' => 5,
            ]),
            json_encode($rules)
        );
    }

    /**
     * @since 1.1.0
     */
    public function testRulesAreIterable()
    {
        $this->assertIsIterable(new ValidationRuleSet($this->getMockRulesRegister()));
    }

    /**
     * @since 1.1.0
     */
    public function testClosuresMustHaveAtLeastTwoParameters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Validation rule closure must accept between 2 and 4 parameters, 1 given.');

        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value) {
        });
    }

    /**
     * @since 1.1.0
     */
    public function testClosureMustHaveAtMostFourParameters()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Validation rule closure must accept between 2 and 4 parameters, 5 given.');

        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value, $fail, $message, $attribute, $extra) {
        });
    }

    /**
     * @since 1.1.0
     */
    public function testClosureSecondParameterMustBeClosure()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage(
            'Validation rule closure must accept a Closure as the second parameter, int given.'
        );

        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value, int $fail) {
        });
    }

    /**
     * @since 1.1.0
     */
    public function testClosureThirdParameterMustBeString()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Validation rule closure must accept a string as the third parameter, int given.');

        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value, $fail, int $message) {
        });
    }

    /**
     * @since 1.1.0
     */
    public function testClosureFourthParameterMustBeArray()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Validation rule closure must accept a array as the fourth parameter, int given.');

        $rules = new ValidationRuleSet($this->getMockRulesRegister());
        $rules->rules(static function ($value, $fail, $message, int $attribute) {
        });
    }

    /**
     * @since 1.1.0
     */
    private function getMockRulesRegister(): ValidationRulesRegistrar
    {
        $register = new ValidationRulesRegistrar();
        $register->register(Required::class);
        $register->register(Size::class);

        return $register;
    }
}

