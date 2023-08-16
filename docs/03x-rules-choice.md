# Choice

Validates that a value (or multiple values) exist in a given set of choices.

```php
Choice(
    array $constraints, 
    bool $multiple = false, 
    ?int $minConstraint = null, 
    ?int $maxConstraint = null,
    string $message = 'The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
    string $multipleMessage = 'The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".',
    string $minMessage = 'The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.',
    string $maxMessage = 'The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.'
);
```

## Basic Usage

```php
// Single choice
Validator::choice(['red', 'green', 'blue'])->validate('green'); // true
Validator::choice(['red', 'green', 'blue'])->validate('yellow'); // false

// Multiple choices
Validator::choice(['red', 'green', 'blue'], multiple: true)->validate(['red', 'blue']); // true;
Validator::choice(['red', 'green', 'blue'], multiple: true)->validate(['red', 'yellow']); // false;

// Multiple with minimum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, minConstraint: 2)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, minConstraint: 2)->validate(['red']); // false

// Multiple with maximum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, maxConstraint: 2)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, maxConstraint: 2)->validate(['red', 'green', 'blue']); // false

// Multiple with minimum and maximum number of choices
Validator::choice(['red', 'green', 'blue'], multiple: true, minConstraint: 2, maxConstraint: 3)->validate(['red', 'blue']); // true
Validator::choice(['red', 'green', 'blue'], multiple: true, minConstraint: 2, maxConstraint: 3)->validate(['red']); // false
```

> **Note**
> An `UnexpectedValueException` will be thrown when `multiple` is `true` and the input value is not an `array`.

> **Note**
> An `UnexpectedValueException` will be thrown when the `minConstraint` value is greater than or equal to the `maxConstraint` value.

## Options

### `constraints`

type: `array` `required`

Collection of constraint choices to be validated against the input value.

### `multiple`

type: `bool` default: `false`

If this option is `true`, validation against an `array` of input values is enabled. 
Each element of the input array must be a valid choice, otherwise it will fail.

### `minConstraint`

type: `?int` default: `null`

If `multiple` is `true`, set a minimum number of input values to be required.

For example, if `minConstraint` is 2, the input array must have at least 2 values.

### `maxConstraint`

type: `?int` default: `null`

If `multiple` is `true`, set a maximum number of input values to be required.

For example, if `maxConstraint` is 2, the input array must have at most 2 values.

### `message`

type `string` default: `The "{{ name }}" value is not a valid choice, "{{ value }}" given. Accepted values are: "{{ constraints }}".`

Message that will be shown if input value is not a valid choice.

The following parameters are available:

| Parameter           | Description                |
|---------------------|----------------------------|
| `{{ value }}`       | The current invalid value  |
| `{{ name }}`        | Name of the invalid value  |
| `{{ constraints }}` | The array of valid choices |

### `multipleMessage`

type `string` default: `The "{{ name }}" value has one or more invalid choices, "{{ value }}" given. Accepted values are: "{{ constraints }}".`

Message that will be shown when `multiple` is `true` and at least one of the input array values is not a valid choice.

The following parameters are available:

| Parameter           | Description                |
|---------------------|----------------------------|
| `{{ value }}`       | The current invalid value  |
| `{{ name }}`        | Name of the invalid value  |
| `{{ constraints }}` | The array of valid choices |

### `minMessage`

type `string` default: `The "{{ name }}" value must have at least {{ minConstraint }} choices, {{ numValues }} choices given.`

Message that will be shown when `multiple` is `true` and input array has fewer values than the defined in `minConstraint`.

The following parameters are available:

| Parameter             | Description                          |
|-----------------------|--------------------------------------|
| `{{ value }}`         | The current invalid value            |
| `{{ numValues }}`     | The current invalid number of values |
| `{{ name }}`          | Name of the invalid value            |
| `{{ constraints }}`   | The array of valid choices           |
| `{{ minConstraint }}` | The minimum number of valid choices  |
| `{{ maxConstraint }}` | The maximum number of valid choices  |

### `maxMessage`

type `string` default: `The "{{ name }}" value must have at most {{ maxConstraint }} choices, {{ numValues }} choices given.`

Message that will be shown when `multiple` is `true` and input array has more values than the defined in `maxConstraint`.

The following parameters are available:

| Parameter             | Description                          |
|-----------------------|--------------------------------------|
| `{{ value }}`         | The current invalid value            |
| `{{ numValues }}`     | The current invalid number of values |
| `{{ name }}`          | Name of the invalid value            |
| `{{ constraints }}`   | The array of valid choices           |
| `{{ minConstraint }}` | The minimum number of valid choices  |
| `{{ maxConstraint }}` | The maximum number of valid choices  |