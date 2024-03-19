# Using Yet Another PHP Validator

- [Usage](#usage)
  - [Fluent](#fluent)
  - [Dependency Injection](#dependency-injection)
- [Methods](#methods)
  - [assert](#assert)
  - [validate](#validate)
  - [getRules](#getrules)
  - [addRule](#addrule)
- [Error Handling](#error-handling)
- [Custom Error Messages](#custom-error-messages)

## Usage

This library allows you to validate data in two different ways:
- In a fluent way, making use of magic methods. The goal is to be able to create a set of rules with minimum setup;
- In a traditional way, making use of dependency injection. You may not like the fluent approach, and prefer to work this way.

Both should work exactly the same.

### Fluent

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

/**
 * @throws ValidationException
 */
function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'unit system');
    
    // ...
}
```

### Dependency Injection

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Rule;
use ProgrammatorDev\Validator\Validator;

/**
 * @throws ValidationException
 */
function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    (new Validator(new Rule\Range(-90, 90)))->assert($latitude, 'latitude');
    (new Validator(new Rule\Range(-180, 180)))->assert($longitude, 'longitude');
    (new Validator(new Rule\NotBlank(), new Rule\Choice(['METRIC', 'IMPERIAL'])))->assert($unitSystem, 'unit system');

    // ...
}
```

## Methods

### `assert`

This method throws a `ValidationException` when a rule fails, otherwise nothing is returned.

```php
/**
 * @throws ValidationException
 */
assert(mixed $value, ?string $name = null): void;
```

An example on how to handle an error:

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'unit system');
    
    // ...
}

try {
    getWeatherTemperature(latitude: 100, longitude: 50, unitSystem: 'METRIC');
}
catch (ValidationException $exception) {
    echo $exception->getMessage(); // The latitude value should be between -90 and 90, 100 given.
}
```
> [!NOTE]
> Check the [Error Handling](#error-handling) section for more information.

> [!NOTE]
> The example only shows one usage approach, but both Fluent and Dependency Injection should work the same.
> Check the [Usage](#usage) section for more information.

### `validate`

This method always returns a `bool` when a rule fails, useful for conditions.

```php
validate(mixed $value): bool
```

An example:

```php
use ProgrammatorDev\Validator\Validator;

if (!Validator::range(-90, 90)->validate($latitude)) {
    // Do something...
} 
```

> [!NOTE]
> The example only shows one usage approach, but both Fluent and Dependency Injection should work the same.
> Check the [Usage](#usage) section for more information.

### `getRules`

Returns an array with the defined set of rules.

```php
/**
 * @return RuleInterface[] 
 */
getRules(): array
```

An example:

```php
use ProgrammatorDev\Validator\Rule;
use ProgrammatorDev\Validator\Validator;

$validator = new Validator(new Rule\GreaterThanOrEqual(0), new Rule\LessThanOrEqual(100));

print_r($validator->getRules());

// Array ( 
//    [0] => ProgrammatorDev\Validator\Rule\GreaterThanOrEqual Object 
//    [1] => ProgrammatorDev\Validator\Rule\LessThanOrEqual Object
// ) 
```

> [!NOTE]
> The example only shows one usage approach, but both Fluent and Dependency Injection should work the same.
> Check the [Usage](#usage) section for more information.

### `addRule`

Adds a rule to a set of rules. May be useful for conditional validations.

```php
addRule(RuleInterface $rule): self
```

An example:

```php
use ProgrammatorDev\Validator\Rule;
use ProgrammatorDev\Validator\Validator;

function calculateDiscount(float $price, float $discount, string $type): float
{
    $discountValidator = new Validator(new GreaterThan(0));
    
    if ($type === 'PERCENT') {
        $discountValidator->addRule(new Rule\LessThanOrEqual(100));
    }
    
    $discountValidator->assert($discount, 'discount');
    
    // ...
}
```

> [!NOTE]
> The example only shows one usage approach, but both Fluent and Dependency Injection should work the same.
> Check the [Usage](#usage) section for more information.

## Error Handling

When using the [`assert`](#assert) method, an exception is thrown when a rule fails.

Each rule has a unique exception, formed by the name of the rule followed by the word Exception, like `RuleNameException`.
The following shows an example:

```php
use ProgrammatorDev\Validator\Exception;
use ProgrammatorDev\Validator\Validator;

try {
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'unit system');
}
catch (Exception\RangeException $exception) {
    // Do something when Range fails
}
catch (Exception\NotBlankException $exception) {
    // Do something when NotBlank fails
}
catch (Exception\ChoiceException $exception) {
    // Do something when Choice fails
}
```

To catch all errors with a single exception, you can use the `ValidationException`:

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

try {
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'unit system');
}
catch (ValidationException $exception) {
    // Do something when a rule fails
    echo $exception->getMessage();
}
```

When using both the [`assert`](#assert) or [`validate`](#validate) methods, 
an `UnexpectedValueException` is thrown when the provided input data is not valid to perform the validation. 

For example, when trying to compare a date with a string:

```php
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Validator;

try {
    Validator::greaterThanOrEqual(new DateTime('today'))->validate('alpha');
}
catch (UnexpectedValueException $exception) {
    echo $exception->getMessage(); // Cannot compare a type "string" with a type "DateTime".
}
```

## Custom Error Messages

All rules have at least one error message that can be customized (some rules have more than one error message for different case scenarios).

Every message has a list of dynamic parameters to help create an intuitive error (like the invalid value, constraints, names, and others).
To check what parameters and messages are available, look into the Options section in the page of a rule. 
Go to [Rules](03-rules.md) to see all available rules.

The following example uses the [Choice](03-rules_choice) rule with a custom error message:

```php
use ProgrammatorDev\Validator\Validator;

Validator::choice(
    constraints: ['red', 'green', 'blue'],
    message: '{{ value }} is not a valid {{ name }}! You must select one of {{ constraints }}.'
)->assert('yellow', 'color');

// Throws: "yellow" is not a valid color! You must select one of ["red", "green", "blue"].
```