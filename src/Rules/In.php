<?php

namespace StellarWP\Validation\Rules;

use Closure;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

class In implements ValidationRule, ValidatesOnFrontEnd
{
    /**
     * @var array
     */
    protected $acceptedValues;

    /**
     * @unreleased
     */
    public static function id(): string
    {
        return 'in';
    }

    /**
     * @unreleased
     */
    public function __construct(...$acceptedValues)
    {
        if (empty($acceptedValues)) {
            Config::throwInvalidArgumentException('The In rule requires at least one value to be specified.');
        }

        $this->acceptedValues = $acceptedValues;
    }

    /**
     * @unreleased
     */
    public static function fromString(string $options = null): ValidationRule
    {
        if (empty(trim($options))) {
            Config::throwInvalidArgumentException('The In rule requires at least one value to be specified.');
        }

        $values = explode(',', $options);

        if (empty($values)) {
            Config::throwInvalidArgumentException('The In rule requires at least one value to be specified.');
        }

        return new self(...$values);
    }

    /**
     * @unreleased
     */
    public function __invoke($value, Closure $fail, string $key, array $values)
    {
        if (!in_array($value, $this->acceptedValues, true)) {
            $fail(sprintf(__('%s must be one of %s', '%TEXTDOMAIN%'), '{field}', implode(', ', $this->acceptedValues)));
        }
    }

    /**
     * @unreleased
     */
    public function serializeOption(): array
    {
        return $this->acceptedValues;
    }
}
