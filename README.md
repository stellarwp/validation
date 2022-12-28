# Introduction
This is a PHP validation library that is end-to-end ready. It is useful in any scenario wherein you have a set of values that you would
like validated (and potentially even sanitized). It comes with an extendable list of rules that can be easily used to make using rules
easy and declarative. For example, in its simplest form it may look like:

```php
$values = [
    'name' => 'Bill Murray',
    'age' => 76
];

$validator = new Validator($values, [
    'name' => ['required'],
    'age' => ['required', 'integer', 'min:18', 'max:150']
]);
```

### What "end-to-end ready" means
In the example above it's clear that rules can be represented in a simple string form. Not all rules can be represented this way (more on
that later), but many can. Further, not all rules require access to the database, and can be easily verified in and of themselves. We can
make use of this.

All rules live in the `ValidationRuleSet`. The class supports being used in `json_encode` function, wherein it will generate a list of all
rules which *can be* validated on the front-end. The age validation rules, for example, would generate the following JSON:
```json
{
    "required": true,
    "integer": null,
    "min": 18,
    "max": 150
}
```
This JSON can be sent to the front-end and adapted to a library like [Joi](https://joi.dev/) for front-end validation. This makes the
following flow possible:
1. Define your input rules on the server
2. Pass the rules as JSON to the browser
3. Convert the rules to your own system and validate in the browser
4. Pass the resulting input data to the server
5. Safely re-validate the inputs on the server

Front-end data is not secure, but it makes for a good user-experience. As such, defining it on one place and having it work the same on
both the browser and server is excellent.
