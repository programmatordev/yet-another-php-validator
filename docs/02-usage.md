# Using Yet Another PHP Validator

- Usage
  - Fluent
  - Dependency Injection
- Methods
  - assert
  - validate
  - getRules
  - addRule
- Exception Handling
- Custom Exception Messages

## Usage

This library allows you to use validate data in two different ways:
- In a fluent way, making use of magic methods. The goal is to be able to create a set of rules with minimum setup;
- In a traditional way, making use of dependency injection. You may not like the fluent approach, and prefer to work this way.

Both should work exactly the same.

### Fluent

```php
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

/**
 * @throws ValidationException
 */
function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'Latitude');
    Validator::range(-180, 180)->assert($longitude, 'Longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'Unit System');
    
    // ...
}
```

### Dependency Injection

```php
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

/**
 * @throws ValidationException
 */
function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    (new Validator(new Rule\Range(-90, 90)))->assert($latitude, 'Latitude');
    (new Validator(new Rule\Range(-180, 180)))->assert($longitude, 'Longitude');
    (new Validator(new Rule\NotBlank(), new Rule\Choice(['METRIC', 'IMPERIAL'])))->assert($unitSystem, 'Unit System');

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
assert(mixed $value, string $name): void;
```

An example on how to handle an exception:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'Latitude');
    Validator::range(-180, 180)->assert($longitude, 'Longitude');
    Validator::notBlank()->choice(['METRIC', 'IMPERIAL'])->assert($unitSystem, 'Unit System');
    
    // ...
}

try {
    getWeatherTemperature(latitude: 100, longitude: 50, unitSystem: 'METRIC');
}
catch (ValidationException $exception) {
    echo $exception->getMessage(); // The "Latitude" value should be between "-90" and "90", "100" given.
}
```

> **Note**
> The example only shows one usage approach, but both Fluent and Dependency Injection usage approaches should work the same.
> Check the [Usage](#usage) section for more information.

### `validate`

This method always returns a `bool` when a rule fails, useful for conditions.

```php
validate(mixed $value): bool
```

An example:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

if (!Validator::range(-90, 90)->validate($latitude)) {
    // Do something...
} 
```

> **Note**
> The example only shows one usage approach, but both Fluent and Dependency Injection usage approaches should work the same.
> Check the [Usage](#usage) section for more information.

### `getRules`

Returns an array with the defined set of rules.

```php
/**
 * @returns RuleInterface[] 
 */
getRules(): array
```

An example:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Rule;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

$validator = new Validator(new Rule\GreaterThanOrEqual(0), new Rule\LessThanOrEqual(100));

print_r($validator->getRules());

// Array ( 
//    [0] => ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThanOrEqual Object 
//    [1] => ProgrammatorDev\YetAnotherPhpValidator\Rule\LessThanOrEqual Object
// ) 
```

> **Note**
> The example only shows one usage approach, but both Fluent and Dependency Injection usage approaches should work the same.
> Check the [Usage](#usage) section for more information.

### `addRule`

Adds a rule to a set of rules. May be useful for conditional validations.

```php
addRule(RuleInterface $rule): self
```

An example:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Rule;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

function calculateDiscount(float $price, float $discount, string $type): float
{
    $discountValidator = new Validator(new GreaterThan(0));
    
    if ($type === 'PERCENT') {
        $discountValidator->addRule(new Rule\LessThanOrEqual(100));
    }
    
    $discountValidator->assert($discount, 'Discount');
    
    // ...
}
```

> **Note**
> The example only shows one usage approach, but both Fluent and Dependency Injection usage approaches should work the same. 
> Check the [Usage](#usage) section for more information.

## Exception Handling

## Custom Exception Messages

All rules have at least one error message that can be customized (some rules have more than one error message for different case scenarios).

Every message has a list of dynamic parameters to help create an intuitive error text (like the invalid value, constraints, names, and others).
To check what parameters and messages are available, look into the Options section in the page of a rule. 
Go to [Rules](03-rules.md) to see all available rules.

The following example uses the [Choice](03x-rules-choice.md) rule with a custom error message:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

Validator::choice(
    constraints: ['red', 'green', 'blue'],
    message: '"{{ value }}" is not a valid {{ name }}! You must select one of {{ constraints }}.'
)->assert('yellow', 'color');

// "yellow" is not a valid color! You must select one of [red, green, blue].
```