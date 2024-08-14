<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\LanguageException;
use ProgrammatorDev\Validator\Exception\InvalidOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Intl\Languages;

class Language extends AbstractRule implements RuleInterface
{
    public const ALPHA_2_CODE = 'alpha-2';
    public const ALPHA_3_CODE = 'alpha-3';

    private const CODE_OPTIONS = [
        self::ALPHA_2_CODE,
        self::ALPHA_3_CODE
    ];

    private string $message = 'The {{ name }} value is not a valid language.';

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
            throw new InvalidOptionException('code', self::CODE_OPTIONS);
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        // keep original value for parameters
        $input = \strtolower($value);

        if (
            ($this->code === self::ALPHA_2_CODE && !Languages::exists($input))
            || ($this->code === self::ALPHA_3_CODE && !Languages::alpha3CodeExists($input))
        ) {
            throw new LanguageException(
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