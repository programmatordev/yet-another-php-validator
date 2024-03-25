# Length

Validates that a given string is between a minimum and maximum length.

```php
Length(
    ?int $min = null, 
    ?int $max = null,
    string $charset = 'UTF-8',
    string $countUnit = 'codepoints',
    ?callable $normalizer = null,
    ?string $minMessage = null,
    ?string $maxMessage = null,
    ?string $exactMessage = null,
    ?string $charsetMessage = null
);
```

## Basic Usage

```php
// min value
Validator::length(min: 1)->validate('abc'); // true
Validator::length(min: 5)->validate('abc'); // false

// max value
Validator::length(max: 5)->validate('abc'); // true
Validator::length(max: 1)->validate('abc'); // false

// min and max value
Validator::length(min: 2, max: 4)->validate('abc'); // true
// exact value
Validator::length(min: 3, max: 3)->validate('abc'); // true

// charset
Validator::length(min: 2, charset: 'ASCII')->validate('テスト'); // false

// count unit
Validator::length(max: 1, countUnit: 'bytes')->validate('🔥'); // false
Validator::length(max: 1, countUnit: 'graphemes')->validate('🔥'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when either `min` or `max` options are not given.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `min` value is greater than the `max` value.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `charset` value is not a valid option.
> Check all the supported character encodings [here](https://www.php.net/manual/en/mbstring.supported-encodings.php).

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `countUnit` value is not a valid option.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string` or an object implementing `\Stringable`.

## Options

### `min`

type: `?int` default: `null`

It defines the minimum number of characters required.

### `max`

type: `?int` default: `null`

It defines the maximum number of characters required.

### `charset`

type: `string` default: `UTF-8`

Charset to be used when measuring a string length.

Check all the supported character encodings [here](https://www.php.net/manual/en/mbstring.supported-encodings.php).

### `countUnit`

type: `string` default: `codepoints`

The character count unit to use when measuring a string length.

Available options are:

- `bytes` uses [`strlen`](https://www.php.net/manual/en/function.strlen) to count the length of the string in bytes.
- `codepoints` uses [`mb_strlen`](https://www.php.net/manual/en/function.mb-strlen.php) to count the length of the string in Unicode code points.
- `graphemes` uses [`grapheme_strlen`](https://www.php.net/manual/en/function.grapheme-strlen.php) to count the length of the string in grapheme units.

### `normalizer`

type: `callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to not measure whitespaces at the end of a string:

```php
Validator::length(max: 3)->validate('abc '); // false

Validator::length(max: 3, normalizer: 'trim')->validate('abc '); // true
Validator::length(max: 3, normalizer: fn($value) => trim($value))->validate('abc '); // true
```

### `minMessage`

type: `?string` default: `The {{ name }} value should have {{ min }} characters or more, {{ numChars }} characters given.`

Message that will be shown when the input value has fewer characters than the defined in `min`.

The following parameters are available:

| Parameter         | Description                              |
|-------------------|------------------------------------------|
| `{{ value }}`     | The current invalid value                |
| `{{ name }}`      | Name of the invalid value                |
| `{{ min }}`       | The minimum number of valid characters   |
| `{{ max }}`       | The maximum number of valid characters   |
| `{{ numChars }}`  | The current invalid number of characters |
| `{{ charset }}`   | Selected charset encoding                |
| `{{ countUnit }}` | Selected count unit                      |

### `maxMessage`

type: `?string` default: `The {{ name }} value should have {{ max }} characters or less, {{ numChars }} characters given.`

Message that will be shown when the input value has more characters than the defined in `max`.

The following parameters are available:

| Parameter         | Description                              |
|-------------------|------------------------------------------|
| `{{ value }}`     | The current invalid value                |
| `{{ name }}`      | Name of the invalid value                |
| `{{ min }}`       | The minimum number of valid characters   |
| `{{ max }}`       | The maximum number of valid characters   |
| `{{ numChars }}`  | The current invalid number of characters |
| `{{ charset }}`   | Selected charset encoding                |
| `{{ countUnit }}` | Selected count unit                      |

### `exactMessage`

type: `?string` default: `The {{ name }} value should have exactly {{ min }} characters, {{ numChars }} characters given.`

Message that will be shown when `min` and `max` options have the same value and the input value has a different number of characters.

The following parameters are available:

| Parameter         | Description                              |
|-------------------|------------------------------------------|
| `{{ value }}`     | The current invalid value                |
| `{{ name }}`      | Name of the invalid value                |
| `{{ min }}`       | The minimum number of valid characters   |
| `{{ max }}`       | The maximum number of valid characters   |
| `{{ numChars }}`  | The current invalid number of characters |
| `{{ charset }}`   | Selected charset encoding                |
| `{{ countUnit }}` | Selected count unit                      |

### `charsetMessage`

type: `?string` default: `The {{ name }} value does not match the expected {{ charset }} charset.`

Message that will be shown if the string does not match the given `charset`.

The following parameters are available:

| Parameter         | Description                              |
|-------------------|------------------------------------------|
| `{{ value }}`     | The current invalid value                |
| `{{ name }}`      | Name of the invalid value                |
| `{{ charset }}`   | Selected charset encoding                |

## Changelog

- `0.7.0` Created