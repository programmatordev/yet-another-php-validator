<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\CollectionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class Collection extends AbstractRule implements RuleInterface
{
    private string $message = '{{ message }}';
    private string $extraFieldsMessage = 'The {{ field }} field is not allowed.';
    private string $missingFieldsMessage = 'The {{ field }} field is missing.';

    /** @param array<mixed, Validator> $fields */
    public function __construct(
        private readonly array $fields,
        private readonly bool $allowExtraFields = false,
        ?string $message = null,
        ?string $extraFieldsMessage = null,
        ?string $missingFieldsMessage = null
    )
    {
        $this->message = $message ?? $this->message;
        $this->extraFieldsMessage = $extraFieldsMessage ?? $this->extraFieldsMessage;
        $this->missingFieldsMessage = $missingFieldsMessage ?? $this->missingFieldsMessage;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        try {
            Validator::eachValue(
                validator: Validator::type(Validator::class),
                message: 'At field {{ key }}: {{ message }}'
            )->assert($this->fields);
        }
        catch (ValidationException $exception) {
            throw new UnexpectedValueException($exception->getMessage());
        }

        if (!\is_array($value)) {
            throw new UnexpectedTypeException('array', get_debug_type($value));
        }

        foreach ($this->fields as $field => $validator) {
            if (!isset($value[$field])) {
                throw new CollectionException(
                    message: $this->missingFieldsMessage,
                    parameters: [
                        'field' => $field
                    ]
                );
            }

            try {
                $validator->assert($value[$field], \sprintf('"%s"', $field));
            }
            catch (ValidationException $exception) {
                throw new CollectionException(
                    message: $this->message,
                    parameters: [
                        'field' => $field,
                        'message' => $exception->getMessage()
                    ]
                );
            }
        }

        if (!$this->allowExtraFields) {
            foreach ($value as $field => $fieldValue) {
                if (!isset($this->fields[$field])) {
                    throw new CollectionException(
                        message: $this->extraFieldsMessage,
                        parameters: [
                            'field' => $field
                        ]
                    );
                }
            }
        }
    }
}