<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\CountryException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use Symfony\Component\Intl\Countries;

class Country extends AbstractRule implements RuleInterface
{
    public const ALPHA_2_CODE = 'alpha-2';
    public const ALPHA_3_CODE = 'alpha-3';

    private const CODE_OPTIONS = [
        self::ALPHA_2_CODE,
        self::ALPHA_3_CODE
    ];

    public function __construct(
        private readonly string $code = self::ALPHA_2_CODE,
        private readonly string $message = 'The {{ name }} value is not a valid {{ code }} country code, {{ value }} given.'
    ) {}

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\in_array($this->code, self::CODE_OPTIONS)) {
            throw new UnexpectedValueException(
                \sprintf(
                    'Invalid code "%s". Accepted values are: "%s".',
                    $this->code,
                    \implode(", ", self::CODE_OPTIONS)
                )
            );
        }

        if (!\is_string($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "string", "%s" given.', get_debug_type($value))
            );
        }

        // Keep original value for parameters
        $input = strtoupper($value);

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