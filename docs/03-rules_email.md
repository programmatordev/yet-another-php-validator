# Email

Validates that a value is a valid email address.

```php
Email(
    string $mode = 'html5',
    ?callable $normalizer = null,
    ?string $message = null
);
```

## Basic Usage

```php
// html5 mode (default)
Validator::email()->validate('test@example.com'); // true
Validator::email()->validate('test@example'); // false

// html5-allow-no-tld mode
Validator::email(mode: 'html5-allow-no-tld')->validate('test@example.com'); // true
Validator::email(mode: 'html5-allow-no-tld')->validate('test@example'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a `mode` option is invalid.

## Options

### `mode`

type: `string` default: `html5`

Set this option to define the validation mode.

Available options are:

- `html5` uses the regular expression of an HTML5 email input element, but enforces it to have a TLD extension.
- `html5-allow-no-tld` uses the regular expression of an HTML5 email input element, which allows addresses without a TLD extension.
- `strict` validates an address according to the [RFC 5322](https://datatracker.ietf.org/doc/html/rfc5322) specification.

### `normalizer`

type: `callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to ignore whitespace in the beginning or end of an email address:

```php
Validator::email()->validate('test@example.com '); // false

Validator::email(normalizer: 'trim')->validate('test@example.com '); // true
Validator::email(normalizer: fn($value) => trim($value))->validate('test@example.com '); // true
```

### `message`

type `string` default: `The {{ name }} value is not a valid email address, {{ value }} given.`

Message that will be shown if the input value is not a valid email address.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |
| `{{ mode }}`  | Selected validation mode  |

## Changelog

- `0.6.0` Created