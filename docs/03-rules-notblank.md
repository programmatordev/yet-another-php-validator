# NotBlank

Validates that a value is not equal to a blank string, blank array, `false` or `null`.

## Basic Usage

```php
Validator::notBlank()->validate(''); // false
Validator::notBlank()->validate([]); // false
Validator::notBlank()->validate(false); // false
Validator::notBlank()->validate(null); // false
```

## Options

```php
use ProgrammatorDev\YetAnotherPhpValidator\Rule;

// Defaults
new Rule\NotBlank(
    array $options = [
        'normalizer' => null,
        'message' => 'The "{{ name }}" value should not be blank, "{{ value }}" given.'
    ]  
);
```

### `options`

type: `array`

`normalizer` 

type: `callable` default: `null`

`message`

type: `string` default: `The "{{ name }}" value should not be blank, "{{ value }}" given.`



Normalizer