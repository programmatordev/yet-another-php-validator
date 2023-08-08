# Using Yet Another PHP Validator

- Usage
  - Fluent
  - Dependency Injection
- Validation
  - assert
  - validate

## Usage

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
    (new Validator(new Rule\Range(-90, 90)))
        ->assert($latitude, 'Latitude');
    (new Validator(new Rule\Range(-180, 180)))
        ->assert($longitude, 'Longitude');
    (new Validator(new Rule\NotBlank(), new Rule\Choice(['METRIC', 'IMPERIAL'])))
        ->assert($unitSystem, 'Unit System');

    // ...
}
```

A `addRule` method is also available that may be useful for conditional rules:

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

## Validation

### `assert`

```php
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

// function getWeatherTemperature(float $latitude, float $longitude, string $unitSystem)

try {
    getWeatherTemperature(100, 50, 'METRIC');
}
catch (ValidationException $exception) {
    echo $exception->getMessage(); // The "Latitude" value should be between "-90" and "90", "100" given.
}
```

### `validate`

```php
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

if (!Validator::range(-90, 90)->validate($latitude)) {
    // do something...
} 
```