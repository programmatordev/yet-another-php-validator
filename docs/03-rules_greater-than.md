# GreaterThan

Validates that a value is greater than another value. 
Can compare between strings, numbers and dates.

```php
GreaterThan(
    mixed $constraint,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::greaterThan(10)->validate(20); // true
Validator::greaterThan(10)->validate(10); // false

Validator::greaterThan(1.5)->validate(2.5); // true
Validator::greaterThan(1.5)->validate(1.5); // false

Validator::greaterThan('alpha')->validate('beta'); // true
Validator::greaterThan('alpha')->validate('alpha'); // false

Validator::greaterThan(new DateTime('today'))->validate(new DateTime('tomorrow')); // true
Validator::greaterThan(new DateTime('today'))->validate(new DateTime('today')); // false
```

> [!NOTE]
> String comparison is case-sensitive, meaning that comparing `"hello"` with `"Hello"` is different.
> Check [`strcmp`](https://www.php.net/manual/en/function.strcmp.php) for more information.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when trying to compare incomparable values, like a `string` with an `int`.

## Options

### `constraint`

type: `mixed` `required`

It defines the comparison/minimum value. 
Can be a `string`, `int`, `float` or `DateTimeInterface` object.

### `message`

type: `?string` default: `The {{ name }} value should be greater than {{ constraint }}, {{ value }} given.`

Message that will be shown if the value is not greater than the constraint value.

The following parameters are available:

| Parameter          | Description               |
|--------------------|---------------------------|
| `{{ value }}`      | The current invalid value |
| `{{ name }}`       | Name of the invalid value |
| `{{ constraint }}` | The comparison value      |

## Changelog

- `0.1.0` Created