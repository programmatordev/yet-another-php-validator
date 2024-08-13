# IsNull

Validates that a value is `null`.

Check the [NotNull](03-rules_not-null.md) rule for the opposite validation.

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

type: `?string` default: `The {{ name }} value should be null.`

Message that will be shown if the value is null.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.3.0` Created