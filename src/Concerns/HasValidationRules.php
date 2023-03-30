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
     * @see ValidationRuleSet::rules()
     *
     * @since 1.0.0
     */
    public function rules(...$rules): self
    {
        $this->validationRules->rules(...$rules);

        return $this;
    }

    /**
     * @see ValidationRuleSet::hasRule()
     *
     * @since 1.0.0
     */
    public function hasRule(string $ruleId): bool
    {
        return $this->validationRules->hasRule($ruleId);
    }

    /**
     * @see ValidationRuleSet::hasRules()
     *
     * @since 1.3.1
     */
    public function hasRules(): bool
    {
        return $this->validationRules->hasRules();
    }

    /**
     * @see ValidationRuleSet::getRule()
     *
     * @since 1.0.0
     */
    public function getRule(string $ruleId): ValidationRule
    {
        return $this->validationRules->getRule($ruleId);
    }

    /**
     * @see ValidationRuleSet::replaceRule()
     *
     * @since 1.3.1
     */
    public function replaceRule(string $ruleId, $rule): bool
    {
        return $this->validationRules->replaceRule($ruleId, $rule);
    }

    /**
     * @see ValidationRuleSet::replaceOrAppendRule()
     *
     * @since 1.3.1
     */
    public function replaceOrAppendRule(string $ruleId, $rule): bool
    {
        return $this->validationRules->replaceOrAppendRule($ruleId, $rule);
    }

    /**
     * @see ValidationRuleSet::replaceOrPrependRule()
     *
     * @since 1.3.1
     */
    public function replaceOrPrependRule(string $ruleId, $rule): bool
    {
        return $this->validationRules->replaceOrPrependRule($ruleId, $rule);
    }

    /**
     * @see ValidationRuleSet::forgetRule()
     *
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
