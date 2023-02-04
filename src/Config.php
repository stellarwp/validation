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
 * @since 1.0.0
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
     * @since 1.0.0
     *
     * @param ContainerInterface $container
     */
    public static function setServiceContainer($container)
    {
        self::$container = $container;
    }

    /**
     * @since 1.0.0
     *
     * @return ContainerInterface
     */
    public static function getServiceContainer()
    {
        return self::$container;
    }

    /**
     * @since 1.0.0
     */
    public static function getHookPrefix(): string
    {
        return self::$hookPrefix;
    }

    /**
     * @since 1.0.0
     */
    public static function setHookPrefix(string $prefix)
    {
        self::$hookPrefix = $prefix;
    }

    /**
     * @since 1.0.0
     *
     * @throws ValidationExceptionInterface
     */
    public static function throwValidationException()
    {
        throw new self::$validationExceptionClass(...func_get_args());
    }

    /**
     * @since 1.0.0
     *
     * @return class-string<ValidationExceptionInterface>
     */
    public static function getValidationExceptionClass(): string
    {
        return self::$validationExceptionClass;
    }

    /**
     * @since 1.0.0
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
     * @since 1.0.0
     *
     * @throws InvalidArgumentException
     */
    public static function throwInvalidArgumentException()
    {
        throw new self::$invalidArgumentExceptionClass(...func_get_args());
    }

    /**
     * @since 1.0.0
     *
     * @return class-string<InvalidArgumentException>
     */
    public static function getInvalidArgumentExceptionClass(): string
    {
        return self::$invalidArgumentExceptionClass;
    }

    /**
     * @since 1.0.0
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
     * @since 1.0.0
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
