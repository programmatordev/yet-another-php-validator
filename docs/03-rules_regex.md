# Regex

Validates that a given regular expression pattern is valid.

```php
Regex(
    string $pattern, 
    bool $match = true,
    ?callable $normalizer = null,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::regex('/[a-z]/')->validate('abc'); // true
Validator::regex('/[a-z]/')->validate('123'); // false

// if match is false, assert that the pattern does not match
// in this case, assert that the value does not contain any lowercase letters
Validator::regex('/[a-z]/', match: false)->validate('abc'); // false
Validator::regex('/[a-z]/', match: false)->validate('123'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown if the `pattern` is not a valid regular expression.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string` or an object implementing `\Stringable`.

## Options

### `pattern` `required`

type: `string`

Regular expression pattern to be matched against.

### `match`

type: `bool` default: `true`

- `true` the validation will pass if the given input value matches the regular expression pattern.
- `false` the validation will pass if the given input value *does not* match the regular expression pattern. 

### `normalizer`

type: `callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to not evaluate whitespaces at the end of a string:

```php
// allow all chars except whitespaces
Validator::length(pattern: '/^\S*$/')->validate('abc '); // false

Validator::length(pattern: '/^\S*$/', normalizer: 'trim')->validate('abc '); // true
Validator::length(pattern: '/^\S*$/', normalizer: fn($value) => trim($value))->validate('abc '); // true
```

### `message`

type: `?string` default: `The {{ name }} value is not valid.`

Message that will be shown when the input value does not match the regular expression pattern.

The following parameters are available:

| Parameter       | Description               |
|-----------------|---------------------------|
| `{{ value }}`   | The current invalid value |
| `{{ name }}`    | Name of the invalid value |
| `{{ pattern }}` | The given pattern         |

## Changelog

- `0.8.0` Created