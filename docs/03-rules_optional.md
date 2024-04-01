# Optional

Validates only if value is *not* `null`.

```php
Optional(
    Validator $validator,
);
```

## Basic Usage

```php
Validator::optional(
    Validator::type('int')->greaterThanEqualTo(18)
)->validate(null); // true

Validator::optional(
    Validator::type('int')->greaterThanEqualTo(18)
)->validate(20); // true

Validator::optional(
    Validator::type('int')->greaterThanEqualTo(18)
)->validate(16); // false
```

## Options

### `validator`

type: `Validator` `required`

Validator that will validate the input value only when it is *not* `null`.

## Changelog

- `1.0.0` Created