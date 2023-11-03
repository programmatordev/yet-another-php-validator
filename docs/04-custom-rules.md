# Custom Rules

- [Create a Rule](#create-a-rule)
- [Message Template](#message-template)

## Create a Rule

You can create your own rules to use with this library. 

For that, you need to create an exception that extends the `ValidationException` and a rule that extends the `AbstractRule` and implements the `RuleInterface`.

First, create your custom rule exception...

```php
namespace My\Project\Exception;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;

class CustomRuleException extends ValidationException {}
```

...then create your custom rule class...

```php
namespace My\Project\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Rule\AbstractRule;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;

class CustomRule extends AbstractRule implements RuleInterface
{
    public function assert(mixed $value, string $name): void
    {
        // Do validation
    }
}
```

...and finally, you need to throw your custom exception when your validation fails, so it looks something like the following:

```php
namespace My\Project\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Rule\AbstractRule;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\RuleInterface;
use My\Project\Exception\CustomRuleException;

class CustomRule extends AbstractRule implements RuleInterface
{
    public function assert(mixed $value, string $name): void
    {
        if ($value === 0) {
            throw new CustomRuleException(
                message: 'The {{ name }} value cannot be zero!',
                parameters: [
                    'name' => $name
                ]               
            );
        }
    }
}
```

In the example above, a new custom rule was created that validates if the input value is not zero.

To use your new custom rule, simply do the following:

```php
// Fluent way, notice the rule() method
$validator = Validator::rule(new CustomRule());
// With multiple rules
$validator = Validator::range(-10, 10)->rule(new CustomRule());

// Dependency injection way
$validator = new Validator(new CustomRule());
// With multiple rules
$validator = new Validator(new Range(-10, 10), new CustomRule());

$validator->assert(0, 'test'); // throws: The test value cannot be zero!
$validator->validate(0); // false
```

## Message Template

When an exception extends the `ValidationException`, you're able to create error message templates.
This means that you can have dynamic content in your messages.

To make it work, just pass an associative array with the name and value of your parameters, and they will be available in the message:

```php
// Exception
class FavoriteException extends ValidationException {}

// Rule
class Favorite extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly string $favorite
    )
    
    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->favorite !== $value) {
            throw new FavoriteException(
                message: 'My favorite {{ name }} is {{ favorite }}, not {{ value }}!',
                parameters: [
                    'name' => $name, // {{ name }}
                    'favorite' => $this->favorite, // {{ favorite }}
                    'value' => $value // {{ value }}
                ]
            )
        }
    }
}

// Throws: My favorite animal is "cat", not "human"!
Validator::rule(new Favorite('cat'))->assert('human', 'animal');
```