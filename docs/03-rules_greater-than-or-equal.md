# GreaterThanOrEqual

Validates that a value is greater than or equal to another value. 
Can compare between strings, numbers and dates.

```php
GreaterThanOrEqual(
    mixed $constraint,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::greaterThanOrEqual(10)->validate(20); // true
Validator::greaterThanOrEqual(10)->validate(10); // true

Validator::greaterThanOrEqual(1.5)->validate(2.5); // true
Validator::greaterThanOrEqual(1.5)->validate(1.5); // true

Validator::greaterThanOrEqual('alpha')->validate('beta'); // true
Validator::greaterThanOrEqual('alpha')->validate('alpha'); // true

Validator::greaterThanOrEqual(new DateTime('today'))->validate(new DateTime('tomorrow')); // true
Validator::greaterThanOrEqual(new DateTime('today'))->validate(new DateTime('today')); // true
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

type: `?string` default: `The {{ name }} value should be greater than or equal to {{ constraint }}.`

Message that will be shown if the value is not greater than or equal to the constraint value.

The following parameters are available:

| Parameter          | Description               |
|--------------------|---------------------------|
| `{{ value }}`      | The current invalid value |
| `{{ name }}`       | Name of the invalid value |
| `{{ constraint }}` | The comparison value      |

## Changelog

- `0.1.0` Created