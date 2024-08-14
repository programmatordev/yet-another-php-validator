# URL

Validates that a value is a valid URL address.

```php
Url(
    array $protocols = ['http', 'https'],
    bool $allowRelativeProtocol = false,
    ?callable $normalizer = null,
    ?string $message = null
);
```

## Basic Usage

```php
Validator::url()->validate('https://example.com'); // true

// only allow the https protocol
Validator::url(protocols: ['https'])->validate('http://example.com'); // false
// or allow the ftp protocol too
Validator::url(protocols: ['https', 'ftp'])->validate('ftp://example.com'); // true

// allow relative protocol
Validator::url()->validate('//example.com'); // false
Validator::url(allowRelativeProtocol: true)->validate('//example.com'); // true
Validator::url(allowRelativeProtocol: true)->validate('https://example.com'); // true
```

## Options

### `protocols`

type: `array` default: `['http', 'https']`

Set this option to define the allowed protocols.

### `allowRelativeProtocol`

type: `bool` default: `false`

If this option is `true`, inclusion of a protocol in the URL will be optional.

### `normalizer`

type: `callable` default: `null`

Allows to define a `callable` that will be applied to the value before checking if it is valid.

For example, use `trim`, or pass your own function, to ignore whitespace in the beginning or end of a URL address:

```php
Validator::url()->validate('https://example.com '); // false

Validator::url(normalizer: 'trim')->validate('https://example.com '); // true
Validator::url(normalizer: fn($value) => trim($value))->validate('https://example.com '); // true
```

### `message`

type: `?string` default: `The {{ name }} value is not a valid URL address.`

Message that will be shown if the input value is not a valid URL address.

The following parameters are available:

| Parameter         | Description               |
|-------------------|---------------------------|
| `{{ value }}`     | The current invalid value |
| `{{ name }}`      | Name of the invalid value |
| `{{ protocols }}` | Allowed protocols         |

## Changelog

- `0.6.0` Created