# NotNull

Validates that a value is not `null`.

Check the [IsNull](03-rules_is-null.md) rule for the opposite validation.

```php
NotNull(
    ?string $message = null
);
```

## Basic Usage

```php
// anything else will be true
Validator::notNull()->validate(null); // false
```

## Options

### `message`

type: `?string` default: `The {{ name }} value should not be null.`

Message that will be shown if the value is not null.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.3.0` Created