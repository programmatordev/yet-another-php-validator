<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\EachValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class EachValue extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly Validator $validator,
        private readonly string $message = 'At key "{{ key }}": {{ message }}'
    ) {}

    public function assert(mixed $value, string $name): void
    {
        if (!\is_iterable($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "array|\Traversable", "%s" given.', get_debug_type($value))
            );
        }

        try {
            foreach ($value as $key => $element) {
                $this->validator->assert($element, $name);
            }
        }
        catch (ValidationException $exception) {
            throw new EachValueException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'key' => $key,
                    'element' => $element,
                    'message' => $exception->getMessage()
                ]
            );
        }
    }
}