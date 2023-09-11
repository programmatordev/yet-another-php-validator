## EachValue

Validates every element of an `array` or object implementing `\Traversable` with a given set of rules.

```php
EachValue(
    Validator $validator,
    string $message = 'At key "{{ key }}": {{ message }}'
);
```

## Basic Usage

```php
Validator::eachValue(Validator::notBlank()->greaterThan(1)->lessThan(10))->validate([4, 5, 6]); // true
Validator::eachValue(Validator::notBlank()->greaterThan(1)->lessThan(10))->validate([4, 5, 20]); // false
```

> **Note**
> An `UnexpectedValueException` will be thrown when the input value is not an `array` or an object implementing `\Traversable`.

## Options

### `validator`

type: `Validator` `required`

Validator that will validate each element of an `array` or object implementing `\Traversable`. 

### `message`

type: `string` default: `At "{{ key }}": {{ message }}`

Message that will be shown if at least one input value element is invalid according to the given `validator`.

```php
Validator::eachValue(Validator::notBlank())->assert(['red', 'green', ''], 'Test'); 
// Throws: At key "2": The "Test" value should not be blank, "" given.
```

The following parameters are available:

| Parameter       | Description                                      |
|-----------------|--------------------------------------------------|
| `{{ value }}`   | The current invalid value                        |
| `{{ name }}`    | Name of the invalid value                        |
| `{{ key }}`     | The key of the invalid iterable element          |
| `{{ message }}` | The rule message of the invalid iterable element |