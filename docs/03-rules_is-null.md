# IsNull

Validates that a value is `null`.

```php
IsNull(
    ?string $message = null
);
```

## Basic Usage

```php
// anything else will be false
Validator::isNull()->validate(null); // true
```

## Options

### `message`

type: `?string` default: `The {{ name }} value should be null, {{ value }} given.`

Message that will be shown if the value is null.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.3.0` Created