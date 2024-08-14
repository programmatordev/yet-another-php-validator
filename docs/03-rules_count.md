# Count

Validates that the number of elements of an `array`, or object implementing `\Countable`, is between a minimum and maximum value.

```php
Count(
    ?int $min = null, 
    ?int $max = null,
    ?string $minMessage = null,
    ?string $maxMessage = null,
    ?string $exactMessage = null
);
```

## Basic Usage

```php
// min value
Validator::count(min: 1)->validate(['a', 'b', 'c']); // true
Validator::count(min: 5)->validate(['a', 'b', 'c']); // false

// max value
Validator::count(max: 5)->validate(['a', 'b', 'c']); // true
Validator::count(max: 1)->validate(['a', 'b', 'c']); // false

// min and max value
Validator::count(min: 2, max: 4)->validate(['a', 'b', 'c']); // true
// exact value
Validator::count(min: 3, max: 3)->validate(['a', 'b', 'c']); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when either `min` or `max` options are not given.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not an `array` or an object implementing `\Countable`.

## Options

### `min`

type: `?int` default: `null`

It defines the minimum number of elements required.

### `max`

type: `?int` default: `null`

It defines the maximum number of elements required.

### `minMessage`

type: `?string` default: `The {{ name }} value should contain {{ min }} elements or more.`

Message that will be shown when the input value has fewer elements than the defined in `min`.

The following parameters are available:

| Parameter           | Description                            |
|---------------------|----------------------------------------|
| `{{ value }}`       | The current invalid value              |
| `{{ name }}`        | Name of the invalid value              |
| `{{ min }}`         | The minimum number of valid elements   |
| `{{ max }}`         | The maximum number of valid elements   |
| `{{ numElements }}` | The current invalid number of elements |

### `maxMessage`

type: `?string` default: `The {{ name }} value should contain {{ max }} elements or less.`

Message that will be shown when the input value has more elements than the defined in `max`.

The following parameters are available:

| Parameter           | Description                            |
|---------------------|----------------------------------------|
| `{{ value }}`       | The current invalid value              |
| `{{ name }}`        | Name of the invalid value              |
| `{{ min }}`         | The minimum number of valid elements   |
| `{{ max }}`         | The maximum number of valid elements   |
| `{{ numElements }}` | The current invalid number of elements |

### `exactMessage`

type: `?string` default: `The {{ name }} value should contain exactly {{ min }} elements.`

Message that will be shown when `min` and `max` options have the same value and the input value has a different number of elements.

The following parameters are available:

| Parameter           | Description                            |
|---------------------|----------------------------------------|
| `{{ value }}`       | The current invalid value              |
| `{{ name }}`        | Name of the invalid value              |
| `{{ min }}`         | The minimum number of valid elements   |
| `{{ max }}`         | The maximum number of valid elements   |
| `{{ numElements }}` | The current invalid number of elements |

## Changelog

- `0.7.0` Created