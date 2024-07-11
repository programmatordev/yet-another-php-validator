# CssColor

Validates that a value is a valid CSS color.

```php
CssColor(
    ?array $formats = null,
    ?string $message = null
);
```

## Basic Usage

```php
// by default, all possible ways to define a CSS color are considered valid
Validator::cssColor()->validate('#0f0f0f'); // true
Validator::cssColor()->validate('black'); // true
Validator::cssColor()->validate('rgb(0, 255, 0'); // true
// ...

// restrict allowed formats
Validator::cssColor(formats: ['hex-long'])->validate('#0f0f0f'); // true
Validator::cssColor(formats: ['hex-long'])->validate('rgb(0, 255, 0)'); // false
Validator::cssColor(formats: ['hex-long', 'rgb'])->validate('rgb(0, 255, 0)'); // true
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown when a `formats` option is invalid.

## Options

### `formats`

type: `?array` default: `null`

By default, all possible ways to define a CSS color are considered valid. 
Use this options to restrict the allowed CSS formats. 

Available options are:

#### `hex-long` 

Examples: `#0f0f0f`, `#0F0F0F`

#### `hex-long-with-alpha`

Examples: `#0f0f0f50`, `#0F0F0F50`

#### `hex-short`

Examples: `#0f0`, `#0F0`

#### `hex-short-with-alpha`

Examples: `#0f05`, `#0F05`

#### `basic-named-colors`

Colors names defined in the [W3C list of basic names colors](https://www.w3.org/wiki/CSS/Properties/color/keywords#Basic_Colors).

Examples: `black`, `green`

#### `extended-named-colors`

Colors names defined in the [W3C list of extended names colors](https://www.w3.org/wiki/CSS/Properties/color/keywords#Extended_colors).

Examples: `black`, `aqua`, `darkgoldenrod`, `green`

#### `system-colors`

Colors names defined in the [CSS WG list of system colors](https://drafts.csswg.org/css-color/#css-system-colors).

Examples: `AccentColor`, `VisitedText`

#### `keywords`

Colors names defined in the [CSS WG list of keywords](https://drafts.csswg.org/css-color/#transparent-color).

Examples: `transparent`, `currentColor`

#### `rgb`

Examples: `rgb(0, 255, 0)`, `rgb(0,255,0)`

#### `rgba`

Examples: `rgba(0, 255, 0, 50)`, `rgba(0,255,0,50)`

#### `hsl`

Examples: `hsl(0, 50%, 50%)`, `hsl(0,50%,50%)`

#### `hsla`

Examples: `hsla(0, 50%, 50%, 0.5)`, `hsla(0,50%,50%,0.5)`

### `message`

type: `?string` default: `The {{ name }} value is not a valid CSS color.`

Message that will be shown if the input value is not a valid CSS color.

The following parameters are available:

| Parameter       | Description               |
|-----------------|---------------------------|
| `{{ value }}`   | The current invalid value |
| `{{ name }}`    | Name of the invalid value |
| `{{ formats }}` | Selected formats          |

## Changelog

- `1.2.0` Created