# AtLeastOneOf

Checks that the value satisfies at least one of the given constraints.

```php
/** Validator[] $constraints */
Choice(
    array $constraints,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::atLeastOneOf([
    Validator::isFalse(),
    Validator::type('int')->greaterThanOrEqual(18)
])->validate(false); // true

Validator::atLeastOneOf([
    Validator::isFalse(),
    Validator::type('int')->greaterThanOrEqual(18)
])->validate(20); // true

Validator::atLeastOneOf([
    Validator::isFalse(),
    Validator::type('int')->greaterThanOrEqual(18)
])->validate(true); // false

Validator::atLeastOneOf([
    Validator::isFalse(),
    Validator::type('int')->greaterThanOrEqual(18)
])->validate(16); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a value in the `constraints` array is not an instance of `Validator`.

## Options

### `constraints`

type: `array<int, Validator>` `required`

Collection of constraints to be validated against the input value.
If at least one given constraint is valid, the validation is considered successful.

### `message`

type: `?string` default: `The {{ name }} value should satisfy at least one of the following constraints: {{ messages }}`

Message that will be shown if all given constraints are not valid.

The following parameters are available:

| Parameter        | Description                                     |
|------------------|-------------------------------------------------|
| `{{ value }}`    | The current invalid value                       |
| `{{ name }}`     | Name of the invalid value                       |
| `{{ messages }}` | List of error messages based on the constraints |

## Changelog

- `1.3.0` Created