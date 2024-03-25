# PasswordStrength

Validates that the given password has reached the minimum strength required by the constraint. 
The strength is calculated by measuring the entropy of the password (in bits) based on its length and the number of unique characters.

```php
PasswordStrength(
    string $minStrength = 'medium', 
    ?string $minMessage = null
);
```

## Basic Usage

```php
Validator::passwordStrength()->validate('password'); // false
Validator::passwordStrength()->validate('i8Kq*MBob~2W"=p'); // true
Validator::passwordStrength(minStrength: 'very-strong')->validate('i8Kq*MBob~2W"=p'); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a `minStrength` option is invalid.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string`.

## Options

### `minStrength`

type: `string` default: `medium`

Sets the minimum strength of the password in entropy bits.
The entropy is calculated using the formula [here](https://www.pleacher.com/mp/mlessons/algebra/entropy.html).

Available options are:

- `weak` entropy between `64` and `79` bits.
- `medium` entropy between `80` and `95` bits.
- `strong` entropy between `96` and `127` bits.
- `very-strong` entropy greater than `128` bits.

All measurements less than `64` bits will fail.

### `message`

type: `?string` default: `The password strength is not strong enough.`

Message that will be shown when the password is not strong enough.

The following parameters are available:

| Parameter           | Description               |
|---------------------|---------------------------|
| `{{ name }}`        | Name of the invalid value |
| `{{ minStrength }}` | Selected minimum strength |

## Changelog

- `0.8.0` Created