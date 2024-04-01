# Collection

Validates each key of an `array`, or object implementing `\Traversable`, with a set of validation constraints.

```php
/** @var array<mixed, Validator> $fields */
Collection(
    array $fields, 
    bool $allowExtraFields = false,
    ?string $message = null,
    ?string $extraFieldsMessage = null,
    ?string $missingFieldsMessage = null
);
```

## Basic Usage

```php
Validator::collection(fields: [
    'name' => Validator::notBlank(),
    'age' => Validator::type('int')->greaterThanOrEqual(18)
])->validate([
    'name' => 'Name',
    'age' => 25
]); // true

Validator::collection(fields: [
    'name' => Validator::notBlank(),
    'age' => Validator::type('int')->greaterThanOrEqual(18)
])->validate([
    'name' => '',
    'age' => 25
]); // false ("name" is blank)

///////////////////////////////

// by default, extra fields are not allowed
Validator::collection(fields: [
    'name' => Validator::notBlank(),
    'age' => Validator::type('int')->greaterThanOrEqual(18)
])->validate([
    'name' => 'Name',
    'age' => 25,
    'email' => 'mail@example.com'
]); // false ("email" field is not allowed)

// to allow extra fields, set option to true
Validator::collection(
    fields: [
        'name' => Validator::notBlank(),
        'age' => Validator::type('int')->greaterThanOrEqual(18)
    ], 
    allowExtraFields: true
)->validate([
    'name' => 'Name',
    'age' => 25,
    'email' => 'mail@example.com'
]); // true

///////////////////////////////

// by default, missing fields are not allowed
Validator::collection(fields: [
    'name' => Validator::notBlank(),
    'age' => Validator::type('int')->greaterThanOrEqual(18)
])->validate([
    'age' => 25
]); // false ("name" is missing)

// but it is possible to use the Optional validation for optiona fields
Validator::collection(fields: [
    'name' => Validator::optional(
        Validator::notBlank()
    ),
    'age' => Validator::type('int')->greaterThanOrEqual(18)
])->validate([
    'age' => 25
]); // true ("name" is optional)
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a value in the `fields` associative array is not an instance of `Validator`.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not an `array` or an object implementing `\Traversable`.

## Options

### `fields`

type: `array<mixed, Validator>` `required`

Associative array with a set of validation constraints for each key.

### `allowExtraFields`

type: `bool` default: `false`

By default, it is not allowed to have fields (array keys) that are not defined in the `fields` option.
If set to `true`, it will be allowed (but not validated).

### `message`

type: `?string` default: `{{ message }}`

Message that will be shown when one of the fields is invalid.

The following parameters are available:

| Parameter       | Description                           |
|-----------------|---------------------------------------|
| `{{ name }}`    | Name of the invalid value             |
| `{{ field }}`   | Name of the invalid field (array key) |
| `{{ message }}` | The rule message of the invalid field |

### `extraFieldsMessage`

type: `?string` default: `The {{ field }} field is not allowed.`

Message that will be shown when the input value has a field that is not defined in the `fields` option
and `allowExtraFields` is set to `false`.

The following parameters are available:

| Parameter       | Description                           |
|-----------------|---------------------------------------|
| `{{ name }}`    | Name of the invalid value             |
| `{{ field }}`   | Name of the invalid field (array key) |

### `missingFieldsMessage`

type: `?string` default: `The {{ field }} field is missing.`

Message that will be shown when the input value *does not* have a field that is defined in the `fields` option.

The following parameters are available:

| Parameter       | Description                           |
|-----------------|---------------------------------------|
| `{{ name }}`    | Name of the invalid value             |
| `{{ field }}`   | Name of the invalid field (array key) |

## Changelog

- `1.0.0` Created