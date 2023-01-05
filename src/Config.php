<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use InvalidArgumentException;
use RuntimeException;
use StellarWP\ContainerContract\ContainerInterface;
use StellarWP\Validation\Exceptions\Contracts\ValidationExceptionInterface;
use StellarWP\Validation\Exceptions\ValidationException;

/**
 * Sets up the validation library for use within the application. It provides a way of overriding various parts of the
 * library to better integrate into the adopting application.
 *
 * @unreleased
 */
class Config
{
    /**
     * @var ContainerInterface The service container to use for resolving dependencies.
     */
    private static $container;

    /**
     * @var string The prefix to add to action and hook filters in the library
     */
    private static $hookPrefix = '';

    /**
     * @var class-string<ValidationExceptionInterface>
     */
    private static $validationExceptionClass = ValidationException::class;

    /**
     * @var class-string<InvalidArgumentException>
     */
    private static $invalidArgumentExceptionClass = InvalidArgumentException::class;

    /**
     * @var bool Whether the library has already been initialized
     */
    private static $initialized = false;

    /**
     * @unreleased
     *
     * @param ContainerInterface $container
     */
    public static function setServiceContainer($container)
    {
        self::$container = $container;
    }

    /**
     * @unreleased
     *
     * @return ContainerInterface
     */
    public static function getServiceContainer()
    {
        return self::$container;
    }

    /**
     * @unreleased
     */
    public static function getHookPrefix(): string
    {
        return self::$hookPrefix;
    }

    /**
     * @unreleased
     */
    public static function setHookPrefix(string $prefix)
    {
        self::$hookPrefix = $prefix;
    }

    /**
     * @unreleased
     *
     * @throws ValidationExceptionInterface
     */
    public static function throwValidationException()
    {
        throw new self::$validationExceptionClass(...func_get_args());
    }

    /**
     * @unreleased
     *
     * @return class-string<ValidationExceptionInterface>
     */
    public static function getValidationExceptionClass(): string
    {
        return self::$validationExceptionClass;
    }

    /**
     * @unreleased
     *
     * @param class-string<ValidationException> $validationExceptionClass
     */
    public static function setValidationExceptionClass(string $validationExceptionClass)
    {
        if (!is_a($validationExceptionClass, ValidationExceptionInterface::class, true)) {
            throw new RuntimeException(
                'The validation exception class must implement the ValidationExceptionInterface'
            );
        }

        self::$validationExceptionClass = $validationExceptionClass;
    }

    /**
     * @unreleased
     *
     * @throws InvalidArgumentException
     */
    public static function throwInvalidArgumentException()
    {
        throw new self::$invalidArgumentExceptionClass(...func_get_args());
    }

    /**
     * @unreleased
     *
     * @return class-string<InvalidArgumentException>
     */
    public static function getInvalidArgumentExceptionClass(): string
    {
        return self::$invalidArgumentExceptionClass;
    }

    /**
     * @unreleased
     *
     * @param class-string<InvalidArgumentException> $invalidArgumentExceptionClass
     */
    public static function setInvalidArgumentExceptionClass(string $invalidArgumentExceptionClass)
    {
        if (!is_a($invalidArgumentExceptionClass, InvalidArgumentException::class, true)) {
            throw new RuntimeException(
                'The invalid argument exception class must extend the InvalidArgumentException'
            );
        }

        self::$invalidArgumentExceptionClass = $invalidArgumentExceptionClass;
    }

    /**
     * @unreleased
     *
     * @return void
     */
    public static function initialize()
    {
        if (self::$initialized) {
            return;
        }

        if (empty(self::$container)) {
            throw new RuntimeException('A service container must be set before initializing the library');
        }

        $serviceProvider = new ServiceProvider();
        $serviceProvider->register();
        self::$initialized = true;
    }
}
