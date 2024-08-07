# IsTrue

Validates that a value is `true`.

```php
IsTrue(
    ?string $message = null
);
```

## Basic Usage

```php
// anything else will be false
Validator::isTrue()->validate(true); // true
```

## Options

### `message`

type: `?string` default: `The {{ name }} value should be true, {{ value }} given.`

Message that will be shown if the value is true.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.3.0` Created