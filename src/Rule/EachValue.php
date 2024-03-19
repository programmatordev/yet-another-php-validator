<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\EachValueException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class EachValue extends AbstractRule implements RuleInterface
{
    private string $message = 'At key {{ key }}: {{ message }}';

    public function __construct(
        private readonly Validator $validator,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_iterable($value)) {
            throw new UnexpectedTypeException('array|\Traversable', get_debug_type($value));
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