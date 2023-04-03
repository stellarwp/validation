<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use ArrayIterator;
use Closure;
use IteratorAggregate;
use JsonSerializable;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use StellarWP\Validation\Contracts\ValidatesOnFrontEnd;
use StellarWP\Validation\Contracts\ValidationRule;
use Traversable;

class ValidationRuleSet implements IteratorAggregate, JsonSerializable
{
    /**
     * @var ValidationRulesRegistrar
     */
    private $register;

    /**
     * @var array<int, ValidationRule|Closure>
     */
    private $rules = [];

    /**
     * @since 1.0.0
     */
    public function __construct(ValidationRulesRegistrar $register)
    {
        $this->register = $register;
    }

    /**
     * Pass a set of validation rules in the form of the rule id, a rule instance, or a closure.
     *
     * @since 1.0.0
     *
     * @param string|ValidationRule|Closure ...$rules
     */
    public function rules(...$rules): self
    {
        foreach ($rules as $rule) {
            $this->rules[] = $this->sanitizeRule($rule);
        }

        return $this;
    }

    /**
     * Prepends a given rule to the start of the rules array.
     *
     * @since 1.3.0
     *
     * @param string|ValidationRule|Closure $rule
     */
    public function prependRule($rule): self
    {
        array_unshift($this->rules, $this->sanitizeRule($rule));

        return $this;
    }

    /**
     * Replaces the given rule at the same index position or appends it if it doesn't exist.
     *
     * @since 1.3.0
     *
     * @param string|ValidationRule|Closure $rule
     *
     * @return bool True if the rule was replaced, false if it was appended.
     */
    public function replaceOrAppendRule(string $ruleId, $rule): bool
    {
        $replaced = $this->replaceRule($ruleId, $rule);

        if (!$replaced) {
            $this->rules($rule);

            return false;
        }

        return true;
    }

    /**
     * Replaces the given rule at the same index position or prepends it if it doesn't exist.
     *
     * @since 1.3.0
     *
     * @param string|ValidationRule|Closure $rule
     *
     * @return bool True if the rule was replaced, false if it was prepended.
     */
    public function replaceOrPrependRule(string $ruleId, $rule): bool
    {
        $replaced = $this->replaceRule($ruleId, $rule);

        if (!$replaced) {
            $this->prependRule($rule);

            return false;
        }

        return true;
    }

    /**
     * Replace a rule with the given id with the given rule at the same index position. Returns true if the rule was
     * replaced, false otherwise.
     *
     * @since 1.3.0
     *
     * @param string|ValidationRule|Closure $rule
     */
    public function replaceRule(string $ruleId, $rule): bool
    {
        foreach ($this->rules as $index => $validationRule) {
            if ($validationRule instanceof ValidationRule && $validationRule::id() === $ruleId) {
                $this->rules[$index] = $this->sanitizeRule($rule);

                return true;
            }
        }

        return false;
    }

    /**
     * Finds and returns the validation rule by id. Does not work for Closure rules.
     *
     * @since 1.0.0
     *
     * @return ValidationRule|null
     */
    public function getRule(string $rule)
    {
        foreach ($this->rules as $validationRule) {
            if ($validationRule instanceof ValidationRule && $validationRule::id() === $rule) {
                return $validationRule;
            }
        }

        return null;
    }

    /**
     * Removes the rules with the given id.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function removeRuleWithId(string $id): self
    {
        $this->rules = array_filter($this->rules, static function ($rule) use ($id) {
            return $rule instanceof ValidationRule && $rule::id() !== $id;
        });

        return $this;
    }

    /**
     * Returns the validation rules.
     *
     * @since 1.0.0
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Returns whether the given rule is present in the validation rules. Does not work with Closure Rules.
     *
     * @since 1.0.0
     */
    public function hasRule(string $rule): bool
    {
        foreach ($this->rules as $validationRule) {
            if ($validationRule instanceof ValidationRule && $validationRule::id() === $rule) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns whether the array has any rules set.
     *
     * @since 1.0.0
     */
    public function hasRules(): bool
    {
        return !empty($this->rules);
    }

    /**
     * Along with the IteratorAggregate interface, we can iterate over the validation rules.
     *
     * @since 1.0.0
     *
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->rules);
    }

    /**
     * Runs through the validation rules and compiles a list of rules that can be used by the front end.
     *
     * Resulting data:
     * [
     *   ruleId => ruleOption,
     *   ...
     * ]
     *
     * @inheritDoc
     *
     * @since 1.0.0
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $rules = [];

        foreach ($this->rules as $rule) {
            if ($rule instanceof ValidatesOnFrontEnd) {
                $rules[$rule::id()] = $rule->serializeOption();
            }
        }

        return $rules;
    }

    /**
     * Sanitizes a given rule by validating the rule and making sure it's safe to use.
     *
     * @since 1.3.0
     *
     * @param mixed $rule
     *
     * @return Closure|ValidationRule
     */
    private function sanitizeRule($rule)
    {
        if ($rule instanceof Closure) {
            $this->validateClosureRule($rule);

            return $rule;
        } elseif ($rule instanceof ValidationRule) {
            return $rule;
        } elseif (is_string($rule)) {
            return $this->getRuleFromString($rule);
        } else {
            Config::throwInvalidArgumentException(
                sprintf(
                    'Validation rule must be a string, instance of %s, or a closure',
                    ValidationRule::class
                )
            );
        }
    }

    /**
     * Validates that a closure rule has the proper parameters to be used as a validation rule.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function validateClosureRule(Closure $closure)
    {
        try {
            $reflection = new ReflectionFunction($closure);
        } catch (ReflectionException $e) {
            Config::throwInvalidArgumentException(
                'Unable to validate closure parameters. Please ensure that the closure is valid.'
            );
        }

        $parameters = $reflection->getParameters();
        $parameterCount = count($parameters);

        if ($parameterCount < 2 || $parameterCount > 4) {
            Config::throwInvalidArgumentException(
                "Validation rule closure must accept between 2 and 4 parameters, $parameterCount given."
            );
        }

        $parameterType = $this->getParameterTypeName($parameters[1]);
        if ($parameterType !== null && $parameterType !== 'Closure') {
            Config::throwInvalidArgumentException(
                "Validation rule closure must accept a Closure as the second parameter, {$parameterType} given."
            );
        }

        $parameterType = $parameterCount > 2 ? $this->getParameterTypeName($parameters[2]) : null;
        if ($parameterType !== null && $parameterType !== 'string') {
            Config::throwInvalidArgumentException(
                "Validation rule closure must accept a string as the third parameter, {$parameterType} given."
            );
        }

        $parameterType = $parameterCount > 3 ? $this->getParameterTypeName($parameters[3]) : null;
        if ($parameterType !== null && $parameterType !== 'array') {
            Config::throwInvalidArgumentException(
                "Validation rule closure must accept a array as the fourth parameter, {$parameterType} given."
            );
        }
    }

    /**
     * Retrieves the parameter type with PHP 7.0 compatibility.
     *
     * @since 1.0.0
     *
     * @return string|null
     */
    private function getParameterTypeName(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if ($type === null) {
            return null;
        }

        // Check if the method exists for PHP 7.0 compatibility (it exits as of PHP 7.1)
        if (method_exists($type, 'getName')) {
            return $type->getName();
        }

        return (string)$type;
    }

    /**
     * Takes a validation rule string and returns the corresponding rule instance.
     *
     * @since 1.3.2 use list syntax for PHP 7.0 compatibility
     * @since 1.0.0
     */
    private function getRuleFromString(string $rule): ValidationRule
    {
        list($ruleId, $ruleOptions) = array_pad(explode(':', $rule, 2), 2, null);

        /**
         * @var ValidationRule $ruleClass
         */
        $ruleClass = $this->register->getRule($ruleId);

        if (!$ruleClass) {
            Config::throwInvalidArgumentException(
                sprintf(
                    'Validation rule with id %s has not been registered.',
                    $ruleId
                )
            );
        }

        return $ruleClass::fromString($ruleOptions);
    }
}
