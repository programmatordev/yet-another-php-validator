# GreaterThan

Validates that a value is greater than another value. Can compare between strings, numbers and dates.

## Basic Usage

```php
Validator::greaterThan(10)->validate(20); // true
Validator::greaterThan(1.5)->validate(2.5); // true
Validator::greaterThan('alpha')->validate('beta'); // true
Validator::greaterThan(new DateTime('today'))->validate(new DateTime('tomorrow')); // true
```

> **Note**
> String comparison is case-sensitive, meaning that comparing `'hello'` with `'Hello'` is different. 
> Check [`strcmp`](https://www.php.net/manual/en/function.strcmp.php) for more information.

> **Note**
> An `UnexpectedValueException` will be thrown when trying to compare incomparable values, like a `string` with an `int`.

## Options

### `constraint`

type: `mixed` `required`

It defines the comparison/minimum value. Can be a `string`, `int`, `float` or `DateTimeInterface` object.

### `message`

type: `string` default: `The "{{ name }}" value should be greater than "{{ constraint }}", "{{ value }}" given.`

Message that will be shown if the value is not greater than the constraint value. 
Check the [Custom Messages]() section for more information.

The following parameters are available:

| Parameter          | Description                       |
|--------------------|-----------------------------------|
| `{{ value }}`      | The current invalid value         |
| `{{ name }}`       | Name of the value being validated |
| `{{ constraint }}` | The comparison value              |