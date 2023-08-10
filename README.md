# Yet Another PHP Validator

Versatile validator focused on validating development code with expressive error messages.

> **Note**
> This library is not in version 1.x mainly because there are so few available rules.
> Hopefully, that should change in the near future.

## Requirements

- PHP 8.1 or higher.

## Installation

You can install the library via [Composer](https://getcomposer.org/):

```bash
composer require programmatordev/yet-another-php-validator
```

To use the library, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once 'vendor/autoload.php';
```

## Basic Usage

Simple usage looks like:

```php
use ProgrammatorDev\YetAnotherPhpValidator\Rule;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

// Do this...
$validator = Validator::notBlank()->greaterThanOrEqual(18);

// Or this...
$validator = new Validator(
    new Rule\NotBlank(), 
    new Rule\GreaterThanOrEqual(18)
);

// Validate with these:
$validator->validate(16); // returns bool: false
$validator->assert(16, 'Age'); // throws exception: The "Age" value should be greater than or equal to "18", "16" given.
```

## Documentation

- [Get Started](docs/01-get-started.md)
- [Usage](docs/02-usage.md)
  - [Usage](docs/02-usage.md#usage)
  - [Methods](docs/02-usage.md#methods)
  - [Error Handling](docs/02-usage.md#error-handling)
  - [Custom Error Messages](docs/02-usage.md#custom-error-messages)
- [Rules](docs/03-rules.md)
- [Custom Rules](docs/04-custom-rules.md)

## Contributing

Any form of contribution to improve this library will be welcome and appreciated.
Make sure to open a pull request or issue.

## Acknowledgments

This library is inspired by [respect/validation](https://github.com/Respect/Validation) and [symfony/validator](https://symfony.com/doc/current/validation.html).

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.