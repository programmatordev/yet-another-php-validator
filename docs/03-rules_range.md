# Range

Validates that a value is between a minimum and maximum value.
Can compare between strings, numbers and dates.

```php
Range(
    mixed $minConstraint,
    mixed $maxConstraint,
    string $message = 'The {{ name }} value should be between {{ minConstraint }} and {{ maxConstraint }}, {{ value }} given.'
);
```

## Basic Usage

Values equal to the defined minimum and maximum values are considered valid.

```php
Validator::range(1, 20)->validate(10); // true
Validator::range(1, 20)->validate(1); // true

Validator::range(1.5, 3.5)->validate(2.5); // true
Validator::range(1.5, 3.5)->validate(3.5); // true

Validator::range('alpha', 'gamma')->validate('beta'); // true
Validator::range('alpha', 'gamma')->validate('alpha'); // true

Validator::range(new DateTime('yesterday'), new DateTime('tomorrow'))->validate(new DateTime('today')); // true
Validator::range(new DateTime('yesterday'), new DateTime('tomorrow'))->validate(new DateTime('tomorrow')); // true
```

> [!NOTE]
> String comparison is case-sensitive, meaning that comparing `"hello"` with `"Hello"` is different.
> Check [`strcmp`](https://www.php.net/manual/en/function.strcmp.php) for more information.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when trying to compare incomparable values, like a `string` with an `int`.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `minConstraint` value is greater than or equal to the `maxConstraint` value.

## Options

### `minConstraint`

type: `mixed` `required`

It defines the minimum range value.
Can be a `string`, `int`, `float` or `DateTimeInterface` object.

### `maxConstraint`

type: `mixed` `required`

It defines the maximum range value.
Can be a `string`, `int`, `float` or `DateTimeInterface` object.

### `message`

type: `string` default: `The {{ name }} value should be between {{ minConstraint }} and {{ maxConstraint }}, {{ value }} given.`

Message that will be shown if the value is not between the minimum and maximum constraint values.

The following parameters are available:

| Parameter             | Description               |
|-----------------------|---------------------------|
| `{{ value }}`         | The current invalid value |
| `{{ name }}`          | Name of the invalid value |
| `{{ minConstraint }}` | The minimum range value   |
| `{{ maxConstraint }}` | The maximum range value   |

## Changelog

- `0.1.0` Created