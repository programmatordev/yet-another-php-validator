<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\EachKeyException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedTypeException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class EachKey extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly Validator $validator,
        private readonly string $message = 'Invalid key: {{ message }}'
    ) {}

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_iterable($value)) {
            throw new UnexpectedTypeException('array|\Traversable', get_debug_type($value));
        }

        try {
            foreach ($value as $key => $element) {
                $this->validator->assert($key, $name);
            }
        }
        catch (ValidationException $exception) {
            throw new EachKeyException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'key' => $key,
                    'element' => $element,
                    // Replaces string "value" with string "key value" to get a more intuitive error message
                    'message' => \str_replace(' value ', ' key value ', $exception->getMessage())
                ]
            );
        }
    }
}