<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\TimezoneException;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Validator;

class Timezone extends AbstractRule implements RuleInterface
{
    private string $message = 'The {{ name }} value is not a valid timezone.';

    public function __construct(
        private readonly int $timezoneGroup = \DateTimeZone::ALL,
        private ?string $countryCode = null,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if ($this->timezoneGroup === \DateTimeZone::PER_COUNTRY) {
            // Country code is required when using PER_COUNTRY timezone group
            if ($this->countryCode === null) {
                throw new UnexpectedValueException(
                    'A country code is required when timezone group is "\DateTimeZone::PER_COUNTRY".'
                );
            }

            // Normalize country code
            $this->countryCode = strtoupper($this->countryCode);

            try {
                Validator::country()->assert($this->countryCode, 'country code');
            }
            catch (ValidationException $exception) {
                throw new UnexpectedValueException($exception->getMessage());
            }
        }

        if (!\in_array($value, \DateTimeZone::listIdentifiers($this->timezoneGroup, $this->countryCode), true)) {
            throw new TimezoneException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'countryCode' => $this->countryCode
                ]
            );
        }
    }
}