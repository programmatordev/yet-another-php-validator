# LessThanOrEqual

Validates that a value is less than or equal to another value. 
Can compare between strings, numbers and dates.

```php
LessThanOrEqual(
    mixed $constraint,
    string $message = 'The {{ name }} value should be less than or equal to {{ constraint }}, {{ value }} given.'
);
```

## Basic Usage

```php
Validator::lessThanOrEqual(20)->validate(10); // true
Validator::lessThanOrEqual(10)->validate(10); // true

Validator::lessThanOrEqual(2.5)->validate(1.5); // true
Validator::lessThanOrEqual(1.5)->validate(1.5); // true

Validator::lessThanOrEqual('beta')->validate('alpha'); // true
Validator::lessThanOrEqual('alpha')->validate('alpha'); // true

Validator::lessThanOrEqual(new DateTime('today'))->validate(new DateTime('yesterday')); // true
Validator::lessThanOrEqual(new DateTime('today'))->validate(new DateTime('today')); // true
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

type: `string` default: `The {{ name }} value should be less than or equal to {{ constraint }}, {{ value }} given.`

Message that will be shown if the value is not less than or equal to the constraint value.

The following parameters are available:

| Parameter          | Description               |
|--------------------|---------------------------|
| `{{ value }}`      | The current invalid value |
| `{{ name }}`       | Name of the invalid value |
| `{{ constraint }}` | The comparison value      |