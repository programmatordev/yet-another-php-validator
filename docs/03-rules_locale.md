# Locale

Validates that a value is a valid locale code.

```php
Locale(
    bool $canonicalize = false,
    ?string $message = null
);
```

## Basic Usage

```php
// by default, code should be the language code (pt, en, ...)
// or the language code followed by an underscore and the uppercased country code (pt_PT, en_US, ...)
Validator::locale()->validate('pt'); // true
Validator::locale()->validate('pt_PT'); // true
Validator::locale()->validate('pt_pt'); // false
Validator::locale()->validate('pt-PT'); // false
Validator::locale()->validate('pt_PRT'); // false

// canonicalize value before validation
Validator::language(canonicalize: true)->validate('pt_pt'); // true
Validator::language(canonicalize: true)->validate('pt-PT'); // true
Validator::language(canonicalize: true)->validate('pt_PRT'); // true
Validator::language(canonicalize: true)->validate('PT-pt.utf8'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string`.

## Options

### `canonicalize`

type: `bool` default: `false`

If `true`, the input value will be normalized before validation, according to the following [documentation](https://unicode-org.github.io/icu/userguide/locale/#canonicalization).

### `message`

type: `?string` default: `The {{ name }} value is not a valid locale, {{ value }} given.`

Message that will be shown if the input value is not a valid locale code.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |

## Changelog

- `1.1.0` Created