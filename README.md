# Yet Another PHP Validator

[![Latest Version](https://img.shields.io/github/release/programmatordev/yet-another-php-validator.svg?style=flat-square)](https://github.com/programmatordev/yet-another-php-validator/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Tests](https://github.com/programmatordev/yet-another-php-validator/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/programmatordev/yet-another-php-validator/actions/workflows/ci.yml?query=branch%3Amain)

PHP validator with expressive error messages.

## Requirements

- PHP 8.1 or higher.

## Installation

Install the library via [Composer](https://getcomposer.org/):

```bash
composer require programmatordev/yet-another-php-validator
```

## Basic Usage

Simple usage looks like:

```php
use ProgrammatorDev\Validator\Validator;

// do this:
$validator = Validator::type('int')->greaterThanOrEqual(18);

// and validate with these:
$validator->validate(16); // returns bool: false
$validator->assert(16, 'age'); // throws exception: The age value should be greater than or equal to 18, 16 given.
```

## Documentation

- [Get Started](docs/01-get-started.md)
- [How to Use](docs/02-usage.md)
  - [Usage](docs/02-usage.md#usage)
  - [Methods](docs/02-usage.md#methods)
  - [Error Handling](docs/02-usage.md#error-handling)
  - [Custom Error Messages](docs/02-usage.md#custom-error-messages)
- [Rules](docs/03-rules.md)
- [Custom Rules](docs/04-custom-rules.md)

## Contributing

Any form of contribution to improve this library (including requests) will be welcome and appreciated.
Make sure to open a pull request or issue.

## Acknowledgments

This library is inspired by [respect/validation](https://github.com/Respect/Validation) and [symfony/validator](https://github.com/symfony/validator).

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.