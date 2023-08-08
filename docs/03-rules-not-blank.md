# NotBlank

Validates that a value is not equal to a blank string, blank array, `false` or `null`.

## Basic Usage

Bellow are the only cases where the rule will fail, everything else is considered valid 
(you may want to check the [`normalizer`](#normalizer) option for a different behaviour):

```php
Validator::notBlank()->validate(''); // false
Validator::notBlank()->validate([]); // false
Validator::notBlank()->validate(false); // false
Validator::notBlank()->validate(null); // false
```

## Options

### `normalizer`

type: `callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to now allow a string with whitespaces only:

```php
// Existing PHP callables
Validator::notBlank(normalizer: 'trim')->validate(' '); // false

// Function
Validator::notBlank(normalizer: fn($value) => trim($value))->validate(' '); // false
```

### `message`

type: `string` default: `The "{{ name }}" value should not be blank, "{{ value }}" given.`

Message that will be shown if the value is blank. Check the [Custom Messages]() section for more information.

The following parameters are available:

| Parameter     | Description                       |
|---------------|-----------------------------------|
| `{{ value }}` | The current invalid value         |
| `{{ name }}`  | Name of the value being validated |