<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests\Unit;

use Closure;
use InvalidArgumentException;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\Sanitizer;
use StellarWP\Validation\Contracts\ValidationRule;
use StellarWP\Validation\Tests\TestCase;
use StellarWP\Validation\ValidationRulesRegistrar;
use StellarWP\Validation\Validator;

/**
 * @covers \StellarWP\Validation\Validator
 *
 * @unreleased
 */
class ValidatorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidationRulesRegister();
    }

    /**
     * @unreleased
     */
    public function testValidatorPasses()
    {
        $validator = new Validator(
            [
                'name' => ['required'],
                'email' => ['required'],
            ],
            [
                'name' => 'Bill Murray',
                'email' => 'bill@example.com',
            ]
        );

        self::assertTrue($validator->passes());
        self::assertFalse($validator->fails());
    }

    /**
     * @unreleased
     */
    public function testValidatorAcceptsArraysAsRules()
    {
        $validator = new Validator([
            'foo' => ['required'],
            'bar' => ['required'],
        ], [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);

        $this->assertTrue($validator->passes());
    }

    /**
     * @unreleased
     */
    public function testFailingValidations()
    {
        $validator = new Validator([
            'foo' => ['required'],
            'bar' => ['required'],
        ], [
            'foo' => 'foo',
            'bar' => '',
        ]);

        self::assertTrue($validator->fails());
        self::assertFalse($validator->passes());
    }

    /**
     * @unreleased
     */
    public function testReturnsErrorsForFailedValidations()
    {
        $validator = new Validator([
            'foo' => ['required'],
            'bar' => ['required'],
        ], [
            'foo' => 'foo',
            'bar' => '',
        ]);

        self::assertEquals([
            'bar' => 'bar required',
        ], $validator->errors());
    }

    /**
     * @unreleased
     */
    public function testUsesLabelsWhenAvailableInErrorMessage()
    {
        $validator = new Validator([
            'foo' => ['required'],
            'bar' => ['required'],
        ], [
            'foo' => '',
            'bar' => '',
        ], [
            'bar' => 'Bar',
        ]);

        self::assertEquals([
            'foo' => 'foo required',
            'bar' => 'Bar required',
        ], $validator->errors());
    }

    /**
     * @unreleased
     */
    public function testReturnsValidatedValues()
    {
        $validator = new Validator([
            'foo' => ['required'],
            'bar' => ['required'],
        ], [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);

        self::assertEquals([
            'foo' => 'foo',
            'bar' => 'bar',
        ], $validator->validated());
    }

    /**
     * @unreleased
     */
    public function testValuesWithoutRulesAreOmitted()
    {
        $validator = new Validator([
            'foo' => ['required'],
        ], [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);

        self::assertEquals([
            'foo' => 'foo',
        ], $validator->validated());
    }

    /**
     * @unreleased
     */
    public function testRuleArraysWithoutRulesAreConsideredOptional()
    {
        // When no rules are specified, the field is considered optional. This simply means that
        // whatever value is passed in will be returned as validated. Keep in mind that if the rule
        // is entirely omitted then the value will also be omitted.
        $validator = new Validator([
            'foo' => [],
        ], [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);

        self::assertEquals([
            'foo' => 'foo',
        ], $validator->validated());
    }

    /**
     * @unreleased
     */
    public function testRulesWithSanitizationAreApplied()
    {
        $validator = new Validator([
            'name' => ['required'],
            'age' => ['required', 'integer'],
        ], [
            'name' => 'Bill Murray',
            'age' => '72',
        ]);

        self::assertSame([
            'name' => 'Bill Murray',
            'age' => 72,
        ], $validator->validated());
    }

    public function testWithSkipValidationRulesSkipsRemainingRules()
    {
        $validator = new Validator([
            'foo' => ['skip', 'required'],
            'bar' => ['required'],
        ], [
            'foo' => '',
            'bar' => 'bar',
        ]);

        self::assertSame([
            'foo' => '',
            'bar' => 'bar',
        ], $validator->validated());
    }

    /**
     * @unreleased
     */
    public function testInvalidRulesThrowInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Validation rules must be an instance of ValidationRuleSet or a compatible array'
        );

        new Validator([
            'foo' => 'wrong',
        ], [
            'foo' => 'foo',
        ]);
    }

    /**
     * Adds the validation register to the container, and adds a mock validation rule
     *
     * @unreleased
     */
    private function mockValidationRulesRegister()
    {
        Config::getServiceContainer()->singleton(
            ValidationRulesRegistrar::class,
            function () {
                $register = new ValidationRulesRegistrar();
                $register->register(
                    MockRequiredRule::class,
                    MockIntegerRule::class,
                    MockSkipRule::class
                );

                return $register;
            }
        );
    }
}

class MockSkipRule implements ValidationRule
{
    public static function id(): string
    {
        return 'skip';
    }

    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    public function __invoke($value, Closure $fail, string $key, array $values): SkipValidationRules
    {
        return new SkipValidationRules();
    }
}

class MockRequiredRule implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public static function id(): string
    {
        return 'required';
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (empty($value)) {
            $fail('{field} required');
        }
    }
}

class MockIntegerRule implements ValidationRule, Sanitizer
{
    /**
     * @inheritDoc
     */
    public static function id(): string
    {
        return 'integer';
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!is_numeric($value)) {
            $fail('{field} must be an integer');
        }
    }

    /**
     * @inheritDoc
     */
    public function sanitize($value)
    {
        return (int)$value;
    }
}
