<?php

declare(strict_types=1);

namespace StellarWP\Validation\Concerns;

use StellarWP\Validation\Config;
use StellarWP\Validation\Contracts\ValidationRule;
use StellarWP\Validation\ValidationRuleSet;

/**
 * Apply this trait to a class to enable it to have validation rules. These rules may be passed to the front-end
 * or used with the Validator to validate data.
 *
 * @since 1.0.0
 */
trait HasValidationRules
{
    /**
     * @var ValidationRuleSet
     */
    protected $validationRules;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->validationRules = Config::getServiceContainer()->get(ValidationRuleSet::class);
    }

    /**
     * @since 1.0.0
     */
    public function rules(...$rules): self
    {
        $this->validationRules->rules(...$rules);

        return $this;
    }

    /**
     * @since 1.0.0
     */
    public function hasRule(string $ruleId): bool
    {
        return $this->validationRules->hasRule($ruleId);
    }

    /**
     * @since 1.0.0
     */
    public function getRule(string $ruleId): ValidationRule
    {
        return $this->validationRules->getRule($ruleId);
    }

    /**
     * @since 1.0.0
     */
    public function forgetRule(string $ruleId): self
    {
        $this->validationRules->removeRuleWithId($ruleId);

        return $this;
    }

    /**
     * @since 1.0.0
     */
    public function getValidationRules(): ValidationRuleSet
    {
        return $this->validationRules;
    }
}
