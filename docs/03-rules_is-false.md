# IsFalse

Validates that a value is `false`.

Check the [IsTrue](03-rules_is-true.md) rule for a `true` validation.

```php
IsFalse(
    ?string $message = null
);
```

## Basic Usage

```php
// anything else will be false
Validator::isFalse()->validate(false); // true
```

## Options

### `message`

type: `?string` default: `The {{ name }} value should be false, {{ value }} given.`

Message that will be shown if the value is false.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.3.0` Created