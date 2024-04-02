# Choice

Validates that a value (or multiple values) exist in a given set of choices.

```php
Choice(
    array $constraints, 
    bool $multiple = false, 
    ?int $min = null, 
    ?int $max = null,
    ?string $message = null,
    ?string $multipleMessage = null,
    ?string $minMessage = null,
    ?string $maxMessage = null
);
```

## Basic Usage

```php
// single choice
Validator::choice(['red', 'green', 'blue'])->validate('green'); // true
Validator::choice(['red', 'green', 'blue'])->validate('yellow'); // false

// multiple choices
Validator::choice(['red', 'green', 'blue'], multiple: true)->validate(['red', 'blue']); // true;
Validator::choice(['red', 'green', 'blue'], multiple: true)->validate(['red', 'yellow']); // false;

// multiple with minimum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, min: 2)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, min: 2)->validate(['red']); // false

// multiple with maximum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, max: 2)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, max: 2)->validate(['red', 'green', 'blue']); // false

// multiple with minimum and maximum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, min: 2, max: 3)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, min: 2, max: 3)->validate(['red']); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when `multiple` is `true` and the input value is not an `array`.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `min` value is greater than or equal to the `max` value.

## Options

### `constraints`

type: `array` `required`

Collection of constraint choices to be validated against the input value.

### `multiple`

type: `bool` default: `false`

If this option is `true`, validation against an `array` of input values is enabled. 
Each element of the input array must be a valid choice, otherwise it will fail.

### `min`

type: `?int` default: `null`

If `multiple` is `true`, set a minimum number of input values to be required.

For example, if `min` is 2, the input array must have at least 2 values.

### `max`

type: `?int` default: `null`

If `multiple` is `true`, set a maximum number of input values to be required.

For example, if `max` is 2, the input array must have at most 2 values.

### `message`

type: `?string` default: `The {{ name }} value is not a valid choice, {{ value }} given. Accepted values are: {{ constraints }}.`

Message that will be shown if input value is not a valid choice.

The following parameters are available:

| Parameter           | Description                |
|---------------------|----------------------------|
| `{{ value }}`       | The current invalid value  |
| `{{ name }}`        | Name of the invalid value  |
| `{{ constraints }}` | The array of valid choices |

### `multipleMessage`

type: `?string` default: `The {{ name }} value has one or more invalid choices, {{ value }} given. Accepted values are: {{ constraints }}.`

Message that will be shown when `multiple` is `true` and at least one of the input array values is not a valid choice.

The following parameters are available:

| Parameter           | Description                |
|---------------------|----------------------------|
| `{{ value }}`       | The current invalid value  |
| `{{ name }}`        | Name of the invalid value  |
| `{{ constraints }}` | The array of valid choices |

### `minMessage`

type: `?string` default: `The {{ name }} value must have at least {{ min }} choices, {{ numElements }} choices given.`

Message that will be shown when `multiple` is `true` and input array has fewer values than the defined in `min`.

The following parameters are available:

| Parameter           | Description                            |
|---------------------|----------------------------------------|
| `{{ value }}`       | The current invalid value              |
| `{{ name }}`        | Name of the invalid value              |
| `{{ constraints }}` | The array of valid choices             |
| `{{ min }}`         | The minimum number of valid choices    |
| `{{ max }}`         | The maximum number of valid choices    |
| `{{ numElements }}` | The current invalid number of elements |

### `maxMessage`

type: `?string` default: `The {{ name }} value must have at most {{ max }} choices, {{ numElements }} choices given.`

Message that will be shown when `multiple` is `true` and input array has more values than the defined in `max`.

The following parameters are available:

| Parameter           | Description                            |
|---------------------|----------------------------------------|
| `{{ value }}`       | The current invalid value              |
| `{{ name }}`        | Name of the invalid value              |
| `{{ constraints }}` | The array of valid choices             |
| `{{ min }}`         | The minimum number of valid choices    |
| `{{ max }}`         | The maximum number of valid choices    |
| `{{ numElements }}` | The current invalid number of elements |

## Changelog

- `0.1.0` Created