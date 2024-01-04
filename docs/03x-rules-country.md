# Country

Validates that a value is a valid country code.

```php
Country(
    string $code = 'alpha-2',
    string $message = 'The {{ name }} value is not a valid {{ code }} country code, {{ value }} given.'
);
```

## Basic Usage

```php
// Default alpha-2 code
Validator::country()->validate('PT'); // true
Validator::country(code: 'alpha-2')->validate('PT'); // true

// Alpha-3 code
Validator::country(code: 'alpha-3')->validate('PRT'); // true
```

> **Note**
> An `UnexpectedValueException` will be thrown when the `code` value is not a valid option.

> **Note**
> An `UnexpectedValueException` will be thrown when the input value is not a `string`.

## Options

### `code`

type: `string` default: `alpha-2`

Set code type to validate the country. 
Check the [official country codes](https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes) list for more information.

Available options:

- `alpha-2`: two-letter code
- `alpha-3`: three-letter code

### `message`

type: `string` default: `The {{ name }} value is not a valid {{ code }} country code, {{ value }} given.`

Message that will be shown if the input value is not a valid country code.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |
| `{{ code }}`  | Selected code type        |

## Changelog

| Version | Description |
|---------|-------------|
| `0.2.0` | Created     |