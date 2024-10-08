## EachKey

Validates every key of an `array`, or object implementing `\Traversable`, with a given set of rules.

```php
EachKey(
    Validator $validator,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::eachKey(
    Validator::type('string')->notBlank()
)->validate(['red' => '#f00', 'green' => '#0f0']); // true

Validator::eachKey(
    Validator::type('string')->notBlank()
)->validate(['red' => '#f00', 1 => '#0f0']); // false
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not an `array` or an object implementing `\Traversable`.

## Options

### `validator`

type: `Validator` `required`

Validator that will validate each key of an `array` or object implementing `\Traversable`.

### `message`

type: `?string` default: `Invalid key {{ key }}: {{ message }}`

Message that will be shown if at least one input value key is invalid according to the given `validator`.

```php
// throws: Invalid key 1: The value should be of type "string".
Validator::eachKey(
    Validator::type('string')
)->assert(['red' => '#f00', 1 => '#0f0'], 'color');
```

The following parameters are available:

| Parameter       | Description                                      |
|-----------------|--------------------------------------------------|
| `{{ value }}`   | The current invalid value                        |
| `{{ name }}`    | Name of the invalid value                        |
| `{{ key }}`     | The key of the invalid iterable element          |
| `{{ element }}` | The value of the invalid iterable element        |
| `{{ message }}` | The rule message of the invalid iterable element |

## Changelog

- `0.4.0` Created