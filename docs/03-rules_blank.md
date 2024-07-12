# Blank

Validates that a value is equal to an empty string, empty array, `false` or `null`.

Check the [NotBlank](03-rules_not-blank.md) rule for the opposite validation.

```php
Blank(
    ?callable $normalizer = null,
    ?string $message = null
);
```

## Basic Usage

Bellow are the *only* cases where the rule will succeed by default, 
everything else is considered invalid (you may want to check the [`normalizer`](#normalizer) option for a different behaviour):

```php
Validator::blank()->validate(''); // true
Validator::blank()->validate([]); // true
Validator::blank()->validate(false); // true
Validator::blank()->validate(null); // true
```

## Options

### `normalizer`

type: `?callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to not allow a string with whitespaces only:

```php
Validator::blank(normalizer: 'trim')->validate(' '); // true
Validator::blank(normalizer: fn($value) => trim($value))->validate(' '); // true
```

### `message`

type: `?string` default: `The {{ name }} value should be blank, {{ value }} given.`

Message that will be shown if the value is not blank.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.2.0` Created