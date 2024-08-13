<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\CountryException;
use ProgrammatorDev\Validator\Exception\UnexpectedOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Intl\Countries;

class Country extends AbstractRule implements RuleInterface
{
    public const ALPHA_2_CODE = 'alpha-2';
    public const ALPHA_3_CODE = 'alpha-3';

    private const CODE_OPTIONS = [
        self::ALPHA_2_CODE,
        self::ALPHA_3_CODE
    ];

    private string $message = 'The {{ name }} value is not a valid country.';

    public function __construct(
        private readonly string $code = self::ALPHA_2_CODE,
        ?string $message = null
    )
    {
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\in_array($this->code, self::CODE_OPTIONS)) {
            throw new UnexpectedOptionException('code', self::CODE_OPTIONS, $this->code);
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException('string', get_debug_type($value));
        }

        // keep original value for parameters
        $input = \strtoupper($value);

        if (
            ($this->code === self::ALPHA_2_CODE && !Countries::exists($input))
            || ($this->code === self::ALPHA_3_CODE && !Countries::alpha3CodeExists($input))
        ) {
            throw new CountryException(
                message: $this->message,
                parameters: [
                    'name' => $name,
                    'value' => $value,
                    'code' => $this->code
                ]
            );
        }
    }
}