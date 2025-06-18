<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use StellarWP\Validation\Commands\ExcludeValue;
use StellarWP\Validation\Commands\SkipValidationRules;
use StellarWP\Validation\Contracts\Sanitizer;
use StellarWP\Validation\Contracts\ValidationRule;
use Closure;

/**
 * A tool for taking in a set of values and corresponding validation rules, and then validating the values.
 *
 * @since 1.0.0
 */
class Validator
{
    /**
     * @var array<string, ValidationRuleSet>
     */
    private array $ruleSets;

    /**
     * @var array<string, mixed>
     */
    private array $values;

    /**
     * @var array<string, string>
     */
    private array $labels;

    /**
     * @var array<string, string>
     */
    private array $errors = [];

    /**
     * @var array<string, mixed>
     */
    private array $validatedValues = [];

    /**
     * @var bool
     */
    private bool $ranValidationRules = false;

    /**
     * @since 1.0.0
     *
     * @param array<string, ValidationRuleSet|array<string|ValidationRule|Closure>> $ruleSets
     * @param array<string, mixed> $values
     * @param array<string, string> $labels
     */
    public function __construct(array $ruleSets, array $values, array $labels = [])
    {
        $validatedRules = [];
        foreach ($ruleSets as $key => $rule) {
            if (is_array($rule)) {
                $validatedRules[$key] = Config::getServiceContainer()->get(ValidationRuleSet::class)->rules(...$rule);
            } elseif ($rule instanceof ValidationRuleSet) {
                $validatedRules[$key] = $rule;
            } else {
                Config::throwInvalidArgumentException(
                    'Validation rules must be an instance of ValidationRuleSet or a compatible array'
                );
            }
        }

        $this->ruleSets = $validatedRules;
        $this->values = $values;
        $this->labels = $labels;
    }

    /**
     * Returns whether the values failed validation or not.
     *
     * @since 1.0.0
     */
    public function fails(): bool
    {
        return !$this->passes();
    }

    /**
     * Returns whether the values passed validation or not.
     *
     * @since 1.0.0
     */
    public function passes(): bool
    {
        $this->runValidationRules();

        return empty($this->errors);
    }

    /**
     * Runs the validation rules on the values, and stores any resulting errors.
     * Will run only once, and then store the results for subsequent calls.
     *
     * @since 1.0.0
     */
    private function runValidationRules(): void
    {
        if ($this->ranValidationRules) {
            return;
        }

        foreach ($this->ruleSets as $key => $ruleSet) {
            $label = $this->labels[$key] ?? $key;
            $value = $this->values[$key] ?? null;

            $fail = function (string $message) use ($key, $label) {
                $this->errors[$key] = str_ireplace('{field}', $label, $message);
            };

            foreach ($ruleSet as $rule) {
                $command = $rule($value, $fail, $key, $this->values);

                if ($command instanceof SkipValidationRules) {
                    break;
                }

                if ($command instanceof ExcludeValue) {
                    // Skip the rest of the rule and do not store the value
                    continue 2;
                }

                if ($rule instanceof Sanitizer) {
                    $value = $rule->sanitize($value);
                }
            }

            $this->validatedValues[$key] = $value;
        }

        $this->ranValidationRules = true;
    }

    /**
     * Returns the errors that were found during validation.
     *
     * @since 1.0.0
     *
     * @return array<string, string>
     */
    public function errors(): array
    {
        $this->runValidationRules();

        return $this->errors;
    }

    /**
     * Returns the validated values, with any sanitization rules applied.
     *
     * @since 1.0.0
     *
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $this->runValidationRules();

        return $this->validatedValues;
    }
}
