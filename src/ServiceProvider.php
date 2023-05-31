<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use StellarWP\Validation\Rules\Boolean;
use StellarWP\Validation\Rules\Currency;
use StellarWP\Validation\Rules\DateTime;
use StellarWP\Validation\Rules\Email;
use StellarWP\Validation\Rules\Exclude;
use StellarWP\Validation\Rules\ExcludeIf;
use StellarWP\Validation\Rules\ExcludeUnless;
use StellarWP\Validation\Rules\In;
use StellarWP\Validation\Rules\InStrict;
use StellarWP\Validation\Rules\Integer;
use StellarWP\Validation\Rules\Max;
use StellarWP\Validation\Rules\Min;
use StellarWP\Validation\Rules\Nullable;
use StellarWP\Validation\Rules\NullableIf;
use StellarWP\Validation\Rules\NullableUnless;
use StellarWP\Validation\Rules\Numeric;
use StellarWP\Validation\Rules\Optional;
use StellarWP\Validation\Rules\OptionalIf;
use StellarWP\Validation\Rules\OptionalUnless;
use StellarWP\Validation\Rules\Required;
use StellarWP\Validation\Rules\Size;

class ServiceProvider
{
    private $validationRules = [
        Required::class,
        Min::class,
        Max::class,
        Size::class,
        Numeric::class,
        In::class,
        InStrict::class,
        Integer::class,
        Email::class,
        Currency::class,
        Exclude::class,
        ExcludeIf::class,
        ExcludeUnless::class,
        Nullable::class,
        NullableIf::class,
        NullableUnless::class,
        Optional::class,
        OptionalIf::class,
        OptionalUnless::class,
        DateTime::class,
        Boolean::class,
    ];

    /**
     * Registers the validation rules registrar with the container
     */
    public function register()
    {
        Config::getServiceContainer()->singleton(ValidationRulesRegistrar::class, function () {
            $register = new ValidationRulesRegistrar();

            foreach ($this->validationRules as $rule) {
                $register->register($rule);
            }

            do_action(Config::getHookPrefix() . 'register_validation_rules', $register);

            return $register;
        });
    }
}
