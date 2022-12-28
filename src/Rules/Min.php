<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;
use StellarWP\Validation\Exceptions\ValidationException;

/**
 * @unreleased
 */
class Min implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @var int
     */
    private $size;

    /**
     * @unreleased
     */
    public function __construct(int $size)
    {
        if ($size <= 0) {
            Config::throwInvalidArgumentException('Min validation rule requires a non-negative value');
        }

        $this->size = $size;
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public static function id(): string
    {
        return 'min';
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public static function fromString(string $options = null): ValidationRule
    {
        if (!is_numeric($options)) {
            Config::throwInvalidArgumentException('Min validation rule requires a numeric value');
        }

        return new self((int)$options);
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     *
     * @throws ValidationException
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (is_int($value) || is_float($value)) {
            if ($value < $this->size) {
                $fail(sprintf(__('%s must be greater than or equal to %d', 'give'), '{field}', $this->size));
            }
        } elseif (is_string($value)) {
            if (mb_strlen($value) < $this->size) {
                $fail(sprintf(__('%s must be more than or equal to %d characters', 'give'), '{field}', $this->size));
            }
        } else {
            Config::throwValidationException("Field value must be a number or string");
        }
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function serializeOption(): int
    {
        return $this->size;
    }

    /**
     * @unreleased
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @unreleased
     *
     * @return void
     */
    public function size(int $size)
    {
        if ($size <= 0) {
            Config::throwInvalidArgumentException('Min validation rule requires a non-negative value');
        }

        $this->size = $size;
    }
}
