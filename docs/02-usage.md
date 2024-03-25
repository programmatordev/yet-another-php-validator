# Using Yet Another PHP Validator

- [Usage](#usage)
- [Methods](#methods)
  - [assert](#assert)
  - [validate](#validate)
- [Error Handling](#error-handling)
- [Custom Error Messages](#custom-error-messages)

## Usage

This library allows you to validate data with a set of rules with minimum setup:

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

/**
 * @throws ValidationException
 */
public function getWeather(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::choice(['metric', 'imperial'])->assert($unitSystem, 'unit system');
    
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

function getWeather(float $latitude, float $longitude, string $unitSystem): float
{
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::choice(['metric', 'imperial'])->assert($unitSystem, 'unit system');
    
    // ...
}

try {
    getWeather(latitude: 100, longitude: 50, unitSystem: 'metric');
}
catch (ValidationException $exception) {
    echo $exception->getMessage(); // The latitude value should be between -90 and 90, 100 given.
}
```
> [!NOTE]
> Check the [Error Handling](#error-handling) section for more information.

### `validate`

This method always returns a `bool` when a rule fails, useful for conditions.

```php
validate(mixed $value): bool
```

An example:

```php
use ProgrammatorDev\Validator\Validator;

if (!Validator::range(-90, 90)->validate($latitude)) {
    // do something...
} 
```

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
    Validator::choice(['metric', 'imperial'])->assert($unitSystem, 'unit system');
}
catch (Exception\RangeException $exception) {
    // do something when Range fails
}
catch (Exception\NotBlankException $exception) {
    // do something when NotBlank fails
}
catch (Exception\ChoiceException $exception) {
    // do something when Choice fails
}
```

To catch all errors with a single exception, you can use the `ValidationException`:

```php
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

try {
    Validator::range(-90, 90)->assert($latitude, 'latitude');
    Validator::range(-180, 180)->assert($longitude, 'longitude');
    Validator::choice(['metric', 'imperial'])->assert($unitSystem, 'unit system');
}
catch (ValidationException $exception) {
    // do something when a rule fails
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

// throws: "yellow" is not a valid color! You must select one of ["red", "green", "blue"].
```