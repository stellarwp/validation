<?php

namespace StellarWP\Validation\Rules;

use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use StellarWP\Validation\Contracts\Sanitizer;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

/**
 * This rule validates that the given value is a valid date.
 *
 * @unreleased
 */
class DateTime implements ValidationRule, ValidatesOnFrontEnd, Sanitizer
{
    /**
     * @var string|null
     */
    protected $format;

    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'dateTime';
    }

    /**
     * @unreleased
     */
    public static function fromString(string $options = null): ValidationRule
    {
        return new static($options);
    }

    /**
     * @unreleased
     */
    public function __construct(string $format = null)
    {
        $this->format = $format;
    }

    /**
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if ($value instanceof DateTimeInterface) {
            return;
        }

        $failedValidation = function () use ($fail) {
            $fail(sprintf(__('%s must be a valid date', '%TEXTDOMAIN%'), '{field}'));
        };

        try {
            if (!is_string($value) && !is_numeric($value)) {
                $failedValidation();

                return;
            }

            if ($this->format !== null) {
                $date = \DateTime::createFromFormat($this->format, $value);
                if ($date === false || $date->format($this->format) !== $value) {
                    $failedValidation();

                    return;
                }
            }

            if (strtotime($value) === false) {
                $failedValidation();

                return;
            }
        } catch (Exception $exception) {
            $failedValidation();

            return;
        }

        $date = date_parse($value);

        if (!checkdate($date['month'], $date['day'], $date['year'])) {
            $failedValidation();
        }
    }

    /**
     * @unreleased
     */
    public function sanitize($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if ($this->format !== null) {
            return DateTimeImmutable::createFromFormat($this->format, $value);
        }

        return new DateTimeImmutable($value);
    }

    /**
     * @unreleased
     */
    public function serializeOption()
    {
        return $this->format;
    }

}
