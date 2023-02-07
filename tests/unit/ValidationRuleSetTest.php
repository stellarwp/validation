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

