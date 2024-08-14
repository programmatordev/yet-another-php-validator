# Type

Validates that a value is of a specific type.

If an array with multiple types is provided, it will validate if the value is of at least one of the given types.
For example, if `['alpha', 'numeric']` is provided, it will validate if the value is of type `alpha` or of type `numeric`.

```php
Type(
    string|array $constraint,
    ?string $message = null
);
```

## Basic Usage

```php
// single type
Validator::type('string')->validate('green'); // true
Validator::type('alphanumeric')->validate('gr33n'); // true

// multiple types
// validates if value is of at least one of the provided types
Validator::type(['alpha', 'numeric'])->validate('green'); // true (alpha)
Validator::type(['alpha', 'numeric'])->validate('33'); // true (numeric)
Validator::type(['alpha', 'numeric'])->validate('gr33n'); // false (not alpha nor numeric)

// class or interface type
Validator::type(\DateTime::class)->validate(new \DateTime()); // true
Validator::type(\DateTimeInterface::class)->validate(new \DateTime()); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a constraint type, class or interface is invalid.

## Options

### `constraint`

type: `string`|`array` `required`

Type(s) to validate the input value type. 
Can validate instances of classes and interfaces.

If an array with multiple types is provided, it will validate if the value is of at least one of the given types.
For example, if `['alpha', 'numeric']` is provided, it will validate if the value is of type `alpha` or of type `numeric`.

Available data type constraints:

- [`bool`](https://www.php.net/manual/en/function.is-bool.php), [`boolean`](https://www.php.net/manual/en/function.is-bool.php)
- [`int`](https://www.php.net/manual/en/function.is-int.php), [`integer`](https://www.php.net/manual/en/function.is-int.php), [`long`](https://www.php.net/manual/en/function.is-int.php)
- [`float`](https://www.php.net/manual/en/function.is-float.php), [`double`](https://www.php.net/manual/en/function.is-float.php), [`real`](https://www.php.net/manual/en/function.is-float.php)
- [`numeric`](https://www.php.net/manual/en/function.is-numeric.php)
- [`string`](https://www.php.net/manual/en/function.is-string.php)
- [`scalar`](https://www.php.net/manual/en/function.is-scalar.php)
- [`array`](https://www.php.net/manual/en/function.is-array.php)
- [`iterable`](https://www.php.net/manual/en/function.is-iterable.php)
- [`countable`](https://www.php.net/manual/en/function.is-countable.php)
- [`callable`](https://www.php.net/manual/en/function.is-callable.php)
- [`object`](https://www.php.net/manual/en/function.is-object.php)
- [`resource`](https://www.php.net/manual/en/function.is-resource.php)
- [`null`](https://www.php.net/manual/en/function.is-null.php)

Available character type constraints:

- [`alphanumeric`](https://www.php.net/manual/en/function.ctype-alnum)
- [`alpha`](https://www.php.net/manual/en/function.ctype-alpha.php)
- [`digit`](https://www.php.net/manual/en/function.ctype-digit.php)
- [`control`](https://www.php.net/manual/en/function.ctype-cntrl.php)
- [`punctuation`](https://www.php.net/manual/en/function.ctype-punct.php)
- [`hexadecimal`](https://www.php.net/manual/en/function.ctype-xdigit.php)
- [`graph`](https://www.php.net/manual/en/function.ctype-graph.php)
- [`printable`](https://www.php.net/manual/en/function.ctype-print.php)
- [`whitespace`](https://www.php.net/manual/en/function.ctype-space.php)
- [`lowercase`](https://www.php.net/manual/en/function.ctype-lower.php)
- [`uppercase`](https://www.php.net/manual/en/function.ctype-upper.php)

### `message`

type: `?string` default: `The {{ name }} value should be of type {{ constraint }}.`

Message that will be shown if input value is not of a specific type.

The following parameters are available:

| Parameter          | Description               |
|--------------------|---------------------------|
| `{{ value }}`      | The current invalid value |
| `{{ name }}`       | Name of the invalid value |
| `{{ constraint }}` | The valid type(s)         |

## Changelog

- `0.2.0` Created