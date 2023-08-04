# Yet Another PHP Validator

Focused on validating development code with expressive error messages.

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
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

$ageValidator = Validator::notBlank()->greaterThanOrEqual(18);

$ageValidator->validate(16); // returns false
$ageValidator->assert(16, 'Age'); // throws exception: The "Age" value should be greater than or equal to "18", "16" given.
```

## Documentation


## Contributing

Any form of contribution to improve this library will be welcome and appreciated.
Make sure to open a pull request or issue.

## Acknowledgments

This library is inspired by [Respect's Validation](https://github.com/Respect/Validation) and [Symfony's Validator](https://symfony.com/doc/current/validation.html).

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.