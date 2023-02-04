<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;
use StellarWP\Validation\Exceptions\ValidationException;

/**
 * @since 1.0.0
 */
class Size implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @var int
     */
    private $size;

    /**
     * @since 1.0.0
     */
    public function __construct(int $size)
    {
        if ($size <= 0) {
            Config::throwInvalidArgumentException('Size validation rule requires a non-negative value');
        }

        $this->size = $size;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function id(): string
    {
        return 'size';
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public static function fromString(string $options = null): ValidationRule
    {
        if (!is_numeric($options)) {
            Config::throwInvalidArgumentException('Size validation rule requires a numeric value');
        }

        return new self((int)$options);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     *
     * @throws ValidationException
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (is_int($value) || is_float($value)) {
            if ($value != $this->size) {
                $fail(sprintf(__('%s must be exactly %d', '%TEXTDOMAIN%'), '{field}', $this->size));
            }
        } elseif (is_string($value)) {
            if (mb_strlen($value) !== $this->size) {
                $fail(sprintf(__('%s must be exactly %d characters', '%TEXTDOMAIN%'), '{field}', $this->size));
            }
        } else {
            Config::throwValidationException("Field value must be a number or string");
        }
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function serializeOption(): int
    {
        return $this->size;
    }

    /**
     * @since 1.0.0
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @since 1.0.0
     *
     * @return void
     */
    public function size(int $size)
    {
        if ($size <= 0) {
            Config::throwInvalidArgumentException('Size validation rule requires a non-negative value');
        }

        $this->size = $size;
    }
}
