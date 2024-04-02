# Get Started

- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)

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
use ProgrammatorDev\Validator\Validator;

// do this:
$validator = Validator::type('int')->greaterThanOrEqual(18);

// and validate with these:
$validator->validate(16); // returns bool: false
$validator->assert(16, 'age'); // throws exception: The age value should be greater than or equal to 18, 16 given.
```