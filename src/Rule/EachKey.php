<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\EachKeyException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class EachKey extends AbstractRule implements RuleInterface
{
    private string $message = 'Invalid key: {{ message }}';

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