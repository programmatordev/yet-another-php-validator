# Language

Validates that a value is a valid language code.

```php
Language(
    string $code = 'alpha-2',
    ?string $message = null
);
```

## Basic Usage

```php
// default alpha-2 code
Validator::language()->validate('pt'); // true

// alpha-3 code
Validator::language(code: 'alpha-3')->validate('por'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the `code` value is not a valid option.

> [!NOTE]
> An `UnexpectedValueException` will be thrown when the input value is not a `string`.

## Options

### `code`

type: `string` default: `alpha-2`

Set code type to validate the language. 
Check the [official language codes](https://en.wikipedia.org/wiki/List_of_ISO_639_language_codes) list for more information.

Available options:

- `alpha-2`: two-letter code
- `alpha-3`: three-letter code

### `message`

type: `?string` default: `The {{ name }} value is not a valid language.`

Message that will be shown if the input value is not a valid language code.

The following parameters are available:

| Parameter     | Description               |
|---------------|---------------------------|
| `{{ value }}` | The current invalid value |
| `{{ name }}`  | Name of the invalid value |
| `{{ code }}`  | Selected code type        |

## Changelog

- `1.1.0` Created