<?php

declare(strict_types=1);

namespace StellarWP\Validation\Rules\Abstracts;

use StellarWP\Validation\Concerns\HasConditions;
use StellarWP\Validation\Conditions\BasicCondition;
use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;

abstract class ConditionalRule implements ValidationRule, ValidatesOnFrontEnd
{
    use HasConditions;

    /**
     * Supports a simple syntax for defining conditions. Example:
     * - ruleId:field1,value1;field2,value2
     *
     * Each rule is assumed to be a basic condition with an equals operator.
     *
     * @inheritDoc
     */
    public static function fromString(string $options = null): ValidationRule
    {
        if (empty($options)) {
            Config::throwInvalidArgumentException(static::class . ' rule requires at least one condition');
        }

        $rules = explode(';', $options);

        $conditions = [];
        foreach ($rules as $rule) {
            $rule = explode(',', $rule);

            if (count($rule) !== 2) {
                Config::throwInvalidArgumentException(static::class . ' rule requires one field name and one value');
            }

            $conditions[] = new BasicCondition($rule[0], '=', $rule[1]);
        }

        return new static(...$conditions);
    }

    public function serializeOption()
    {
        return $this->conditions->jsonSerialize();
    }
}
