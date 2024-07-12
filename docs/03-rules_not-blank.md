# NotBlank

Validates that a value is not equal to an empty string, empty array, `false` or `null`.

Check the [Blank](03-rules_blank.md) rule for the opposite validation.

```php
NotBlank(
    ?callable $normalizer = null,
    ?string $message = null
);
```

## Basic Usage

Bellow are the *only* cases where the rule will fail by default, 
everything else is considered valid (you may want to check the [`normalizer`](#normalizer) option for a different behaviour):

```php
Validator::notBlank()->validate(''); // false
Validator::notBlank()->validate([]); // false
Validator::notBlank()->validate(false); // false
Validator::notBlank()->validate(null); // false
```

## Options

### `normalizer`

type: `?callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to not allow a string with whitespaces only:

```php
Validator::notBlank(normalizer: 'trim')->validate(' '); // false
Validator::notBlank(normalizer: fn($value) => trim($value))->validate(' '); // false
```

### `message`

type: `?string` default: `The {{ name }} value should not be blank, {{ value }} given.`

Message that will be shown if the value is blank.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `0.1.0` Created