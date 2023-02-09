<?php

declare(strict_types=1);

namespace StellarWP\Validation;

use StellarWP\Validation\Rules\Currency;
use StellarWP\Validation\Rules\Email;
use StellarWP\Validation\Rules\Exclude;
use StellarWP\Validation\Rules\Integer;
use StellarWP\Validation\Rules\Max;
use StellarWP\Validation\Rules\Min;
use StellarWP\Validation\Rules\Nullable;
use StellarWP\Validation\Rules\Numeric;
use StellarWP\Validation\Rules\Optional;
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
        Integer::class,
        Email::class,
        Currency::class,
        Exclude::class,
        Nullable::class,
        Optional::class,
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
