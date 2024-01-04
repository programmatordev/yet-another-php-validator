# LessThan

Validates that a value is less than another value. 
Can compare between strings, numbers and dates.

```php
LessThan(
    mixed $constraint,
    string $message = 'The {{ name }} value should be less than {{ constraint }}, {{ value }} given.'
);
```

## Basic Usage

```php
Validator::lessThan(20)->validate(10); // true
Validator::lessThan(20)->validate(20); // false

Validator::lessThan(2.5)->validate(1.5); // true
Validator::lessThan(2.5)->validate(2.5); // false

Validator::lessThan('beta')->validate('alpha'); // true
Validator::lessThan('beta')->validate('beta'); // false

Validator::lessThan(new DateTime('today'))->validate(new DateTime('yesterday')); // true
Validator::lessThan(new DateTime('today'))->validate(new DateTime('today')); // false
```

> **Note**
> String comparison is case-sensitive, meaning that comparing `"hello"` with `"Hello"` is different.
> Check [`strcmp`](https://www.php.net/manual/en/function.strcmp.php) for more information.

> **Note**
> An `UnexpectedValueException` will be thrown when trying to compare incomparable values, like a `string` with an `int`.

## Options

### `constraint`

type: `mixed` `required`

It defines the comparison/maximum value. 
Can be a `string`, `int`, `float` or `DateTimeInterface` object.

### `message`

type: `string` default: `The {{ name }} value should be less than {{ constraint }}, {{ value }} given.`

Message that will be shown if the value is not less than the constraint value.

The following parameters are available:

| Parameter          | Description               |
|--------------------|---------------------------|
| `{{ value }}`      | The current invalid value |
| `{{ name }}`       | Name of the invalid value |
| `{{ constraint }}` | The comparison value      |

## Changelog

| Version | Description |
|---------|-------------|
| `0.1.0` | Created     |