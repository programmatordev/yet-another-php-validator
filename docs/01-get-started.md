# Get Started

- [Requirements](#requirements)
- [Installation](#installation)
- [Basic Usage](#basic-usage)

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
$validator->assert(16, 'age'); // throws exception: The age value should be greater than or equal to 18.
```