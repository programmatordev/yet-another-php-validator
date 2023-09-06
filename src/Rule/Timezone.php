<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\TimezoneException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class Timezone extends AbstractRule implements RuleInterface
{
    public function __construct(
        private readonly int $timezoneGroup = \DateTimeZone::ALL,
        private ?string $countryCode = null,
        private readonly string $message = 'The "{{ name }}" value is not a valid timezone, "{{ value }}" given.'
    ) {}

    public function assert(mixed $value, string $name): void
    {
        if ($this->countryCode !== null) {
            // Normalize country code
            $this->countryCode = strtoupper($this->countryCode);

            try {
                Validator::country()->assert($this->countryCode, 'countryCode');
            }
            catch (ValidationException $exception) {
                throw new UnexpectedValueException($exception->getMessage());
            }
        }

        if ($this->timezoneGroup === \DateTimeZone::PER_COUNTRY && $this->countryCode === null) {
            throw new UnexpectedValueException(
                'A country code must be given when timezone group is \DateTimeZone::PER_COUNTRY.'
            );
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