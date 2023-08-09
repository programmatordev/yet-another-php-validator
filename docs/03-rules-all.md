## All

Validates every element of an `array` with a given set of rules.

```php
/**
 * @var RuleInterface[] $constraints
 */
All(
    array $constraints,
    string $message = 'At "{{ key }}": {{ message }}'
);
```

## Basic Usage

```php
// One rule per array element
Validator::all([Validator::notBlank(), Validator::greaterThan(1), Validator::lessThan(10)])->validate([4, 5, 6]); // true
Validator::all([Validator::notBlank(), Validator::greaterThan(1), Validator::lessThan(10)])->validate([4, 5, 20]); // false

// Multiple rules per array element
Validator::all([Validator::notBlank()->greaterThan(1)->lessThan(10)])->validate([4, 5, 6]); // true
```

> **Note**
> An `UnexpectedValueException` will be thrown if a `constraints` element does not implement a `RuleInterface`.

> **Note**
> An `UnexpectedValueException` will be thrown when value to be validated is not an `array`.

## Options

### `constraints`

type: `array` `required`

Collection of rules, or validators, to validate each element of an `array`. 
Each element must implement a `RuleInterface`, so it is possible to use a single rule or a full validator set of rules.

### `message`

type: `string` default: `At "{{ key }}": {{ message }}`

Message that will be shown if at least one element of an array is invalid according to the given `constraints`.

```php
Validator::all([Validator::notBlank()])->assert(['red', 'green', ''], 'Test'); // At "2": The "Test" value should not be blank, "" given.
```

The following parameters are available:

| Parameter       | Description                                   |
|-----------------|-----------------------------------------------|
| `{{ value }}`   | The current invalid value                     |
| `{{ name }}`    | Name of the value being validated             |
| `{{ key }}`     | The array key of the invalid array element    |
| `{{ message }}` | The rule message of the invalid array element |