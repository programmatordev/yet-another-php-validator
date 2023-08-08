<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\AllException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\ValidatableTrait;

class All extends AbstractRule implements RuleInterface
{
    use ValidatableTrait;

    private string $message;

    /**
     * @param RuleInterface[] $constraints
     */
    public function __construct(
        private readonly array $constraints,
        ?string $message = null
    )
    {
        $this->message = $message ?? 'At "{{ key }}": {{ message }}';
    }

    public function assert(mixed $value, string $name): void
    {
        if (!$this->isValidatable($this->constraints)) {
            throw new UnexpectedValueException(
                'All constraints must be of type "RuleInterface".'
            );
        }

        if (!\is_array($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "array", "%s" given.', get_debug_type($value))
            );
        }

        try {
            foreach ($value as $key => $input) {
                foreach ($this->constraints as $constraint) {
                    $constraint->assert($input, $name);
                }
            }
        }
        catch (ValidationException $exception) {
            throw new AllException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'key' => $key,
                    'message' => $exception->getMessage()
                ]
            );
        }
    }
}