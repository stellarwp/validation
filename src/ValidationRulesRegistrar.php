<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use StellarWP\Validation\Contracts\ValidationRule;

/**
 * @since 1.0.0
 */
class ValidationRulesRegistrar
{
    /** @var array */
    protected $rules = [];

    /**
     * Register one or many validation rules.
     *
     * @since 1.0.0
     */
    public function register(string ...$rules): self
    {
        foreach ($rules as $rule) {
            $this->registerClass($rule);
        }

        return $this;
    }

    /**
     * Register a validation rule.
     *
     * @since 1.2.1 switch to throwing InvalidArgumentException from Config
     * @since 1.0.0
     */
    private function registerClass(string $class): self
    {
        if (!is_subclass_of($class, ValidationRule::class)) {
            Config::throwInvalidArgumentException(
                sprintf(
                    'Validation rule must implement %s',
                    ValidationRule::class
                )
            );
        }

        $classId = $class::id();

        if (isset($this->rules[$classId])) {
            Config::throwInvalidArgumentException(
                "A validation rule with the id $classId has already been registered."
            );
        }

        $this->rules[$classId] = $class;

        return $this;
    }

    /**
     * Get a validation rule.
     *
     * @return string|null
     * @since 2.12.0
     *
     */
    public function getRule(string $ruleId)
    {
        return $this->rules[$ruleId] ?? null;
    }
}
