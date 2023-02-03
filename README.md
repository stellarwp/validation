# Introduction

This is a PHP validation library that is end-to-end ready. It is useful in any scenario wherein you have a set of values
that you would like validated (and potentially even sanitized). It comes with an extendable list of rules that can be
easily used to make using rules easy and declarative.


## How to use

### Installation

It is recommended to install this library using [Composer](https://getcomposer.org/). To do so, run the following
command:

```bash
composer require stellarwp/validation
```

If using this in WordPress, it is strongly recommended that
you [use Strauss](https://github.com/stellarwp/global-docs/blob/main/docs/strauss-setup.md)
to avoid conflicts with other plugins.

### Configuration and initialization

The library comes with a `Config` class which is used to set up and initialize the library. Here's an example of how to
use it:

```php
use StellarWP\Validation\Config;

Config::setServiceContainer(MyContainer::class); // required
Config::setHookPrefix('my_plugin_'); // recommended

Config::setInvalidArgumentExceptionClass(MyInvalidArgumentException::class); // optional
Config::setValidationExceptionClass(MyValidationException::class); // optional

Config::initialize(); // mounts rules registrar to service container
```

The Service Container is used for dependency injection. The library uses this to store the `ValidationRulesRegistrar`,
which keeps track of all the available rules. It is required to be set, and must implement
the [Container Interface](https://github.com/stellarwp/container-contract).
If you don't have a container, you can use the [StellarWP Container](https://github.com/stellarwp/container) or the
[di52 container](https://github.com/lucatume/di52).

### Validating data

The two main classes in the library are `Validator` and `ValidationRuleSet`. The `Validator` class is used to validate
data, while the `ValidationRuleSet` is used to define the rules for validation. The `Validator` class is used as
follows:

```php
use StellarWP\Validation\Validator;

$values = [
    'name' => 'Bill Murray',
    'age' => 76
];

$labels = [
    'name' => 'Name',
    'age' => 'Age'
];

$validator = new Validator([
    'name' => ['required'],
    'age' => ['required', 'integer', 'min:18', 'max:150']
], $values, $labels);

if ($validator->passes()) {
    $safeData = $validator->validated();
} else {
    $errors = $validator->errors();
}
```

The `Validator` class takes three arguments:
- `$values` - the values to be validated
- `$rules` - the rules to be used for validation
- `$labels` - the labels to be used for error messages

`Validator::passes()` returns true if all rules pass

`Validator::fails()` returns true if any rule fails

`Validator::validated()` returns the validated data — any values without a rule will be removed, ensuring only trusted
data is returned.

`Validator::errors()` returns an array of errors. The keys match the keys of the `$values` array, and the values are
the error messages. If there are no errors an empty array is returned.

### Rule arguments
The `$rules` parameter passed to the `Validator` can either be an array or rules, or a `ValidationRuleSet` instance.

When passing an array, the rules can be defined in three ways:
```php
$rules = [
    'name' => [
        // As a string
        'required',

        // As a Rule instance
        new \StellarWP\Validation\Rules\Min(1),

        // As a closure
        function ($value, Closure $fail, string $key, array $values) {
            if ($value === 'foo') {
                $fail('{field} cannot be foo');
            }
        }
    ]
];
```

### Adding rules to existing classes
Sometimes you have a class which represents an individual value, like a Value Object. In this case, you can use the
[HasValidationRules](src/Concerns/HasValidationRules.php) trait to add validation rules to the class. This trait adds
various methods to the class for managing rules.

## Adding new rules
The library comes with a number of rules out of the box, but you can easily add your own.

### How rules are resolved
When adding rules to a rule set, you can either pass a string, a `Rule` instance, or a closure. When a string is passed,
the library will attempt to resolve it using the [ValidationRulesRegistrar](src/ValidationRulesRegistrar.php). The
static `Rule::id()` method is used to register the rule. So `Min`, for example, has an id of `min`. So when a string
rule is `min:18`, the `Min` rule will be resolved. Additional options can be passed to the rule after the colon.

This may seem like a bit of work, but it allows for easily readable, declarative rules when being used.

### Basic rules
For a class to be a rule, it must implement the [ValidationRule](src/Contracts/ValidationRule.php) interface. See the
interface for documentation on its methods.

### Front-end compatible rules
If a class implements the [ValidatesOnFrontEnd](src/Contracts/ValidatesOnFrontEnd.php) interface.

All rules live in the `ValidationRuleSet`. The class supports being used in `json_encode` function, wherein it will
generate a list of all rules which *can be* validated on the front-end. The age validation rules, for example, would
generate the following JSON:

```json
{
    "required": true,
    "integer": null,
    "min": 18,
    "max": 150
}
```

This JSON can be sent to the front-end and adapted to a library like [Joi](https://joi.dev/) for front-end validation.
This makes the following flow possible:

1. Define your input rules on the server
2. Pass the rules as JSON to the browser
3. Convert the rules to your own system and validate in the browser
4. Pass the resulting input data to the server
5. Safely re-validate the inputs on the server

Front-end data is not secure, but it makes for a good user-experience. As such, defining it on one place and having it
work the same on both the browser and server is excellent.

### Sanitizing Rules
Rules can also provide sanitization — that is, they can modify the value before it is returned. To do this, implement
the [Sanitizer](src/Contracts/Sanitizer.php) interface. Note that sanitization occurs *after* validation, so a value
will only be sanitized if it first passes validation.

Finally, keep in mind that sanitization affects the value before it is sent to the following rule. This is useful in
cases where you want to validate a value based on its type. Take age for example:

```php
$rules = [
    'age' => ['required', 'integer', 'min:18', 'max:150']
];
```

The `integer` rule converts the value into an integer. So `min:18` will check that the age numerically greater than or
equal to 18. Without the `integer` rule, if `age` was a string, then `min:18` would count the number of characters, not
the numerical value.
