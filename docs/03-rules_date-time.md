# DateTime

Validates that a given value is a valid datetime in a specific format.

```php
DateTime(
    string $format = 'Y-m-d H:i:s', 
    ?string $message = null
);
```

## Basic Usage

```php
// default "Y-m-d H:i:s"
Validator::dateTime()->validate('2024-01-01 00:00:00'); // true
Validator::dateTime()->validate('2024-01-01'); // false

// validate date
Validator::dateTime(format: 'Y-m-d')->validate('2024-01-01'); // true
Validator::dateTime(format: 'Y-m-d')->validate('2024-01-35'); // false

// validate time
Validator::dateTime(format: 'H:i:s')->validate('21:00:00'); // true
Validator::dateTime(format: 'H:i:s')->validate('35:00:00'); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string` or an object implementing `\Stringable`.

## Options

### `format`

type: `string` default: `Y-m-d H:i:s`

Format of the datetime to be validated.
Check all formatting options [here](https://www.php.net/manual/en/datetimeimmutable.createfromformat.php).

### `message`

type: `?string` default: `The {{ name }} value is not a valid datetime.`

Message that will be shown when the input value is not a valid datetime.

The following parameters are available:

| Parameter      | Description               |
|----------------|---------------------------|
| `{{ value }}`  | The current invalid value |
| `{{ name }}`   | Name of the invalid value |
| `{{ format }}` | The datetime format       |

## Changelog

- `0.8.0` Created