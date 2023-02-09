<?php

declare(strict_types=1);

namespace StellarWP\Validation\Tests;

use Codeception\Test\Unit;
use lucatume\DI52\Container;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidationRule;
use Traversable;

class TestCase extends Unit
{
    protected $backupGlobals = false;

    protected function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        Config::setServiceContainer($container);
    }

    /**
     * Asserts that a given validation rule passes.
     *
     * @param mixed $value
     *
     * @return void
     */
    public static function assertValidationRulePassed(
        ValidationRule $rule,
        $value,
        string $key = '',
        array $values = [],
        bool $shouldPass = true
    ) {
        $error = null;
        $fail = function ($message) use (&$error) {
            $error = $message;
        };

        $rule($value, $fail, $key, $values);

        if ($shouldPass) {
            self::assertNull($error, 'Validation rule failed. Value: ' . print_r($value, true));
        } else {
            self::assertNotNull($error, 'Validation rule passed. Value: ' . print_r($value, true));
        }
    }

    /**
     * Asserts that a given validation rule fails.
     *
     * @param mixed $value
     *
     * @return void
     */
    public static function assertValidationRuleFailed(
        ValidationRule $rule,
        $value,
        string $key = '',
        array $values = []
    ) {
        self::assertValidationRulePassed($rule, $value, $key, $values, false);
    }

    public static function assertValidationRuleDoesReturnCommandInstance(
        ValidationRule $rule,
        string $commandClass,
        $value = null,
        string $key = '',
        array $values = []
    ) {
        $fail = static function () {
        };

        $command = $rule($value, $fail, $key, $values);

        self::assertInstanceOf($commandClass, $command);
    }

    public static function assertValidationRuleDoesNotReturnCommandInstance(
        ValidationRule $rule,
        string $commandClass,
        $value = null,
        string $key = '',
        array $values = []
    ) {
        $fail = static function () {
        };

        $command = $rule($value, $fail, $key, $values);

        self::assertNotInstanceOf($commandClass, $command);
    }

    public static function assertIsIterable($actual, $message = '')
    {
        if (\function_exists('is_iterable') === true) {
            // PHP >= 7.1.
            // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.is_iterableFound
            self::assertTrue(is_iterable($actual), $message);
        } else {
            // PHP < 7.1.
            $result = (\is_array($actual) || $actual instanceof Traversable);
            self::assertTrue($result, $message);
        }
    }
}
