<?php

namespace ProgrammatorDev\Validator\Rule;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\NoRFCWarningsValidation;
use ProgrammatorDev\Validator\Exception\EmailException;
use ProgrammatorDev\Validator\Exception\InvalidOptionException;
use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;

class Email extends AbstractRule implements RuleInterface
{
    public const MODE_HTML5 = 'html5';
    public const MODE_HTML5_ALLOW_NO_TLD = 'html5-allow-no-tld';
    public const MODE_STRICT = 'strict';

    private const EMAIL_MODES = [
        self::MODE_HTML5,
        self::MODE_HTML5_ALLOW_NO_TLD,
        self::MODE_STRICT
    ];

    private const EMAIL_PATTERNS = [
        self::MODE_HTML5 => '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/',
        self::MODE_HTML5_ALLOW_NO_TLD => '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/'
    ];

    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value is not a valid email address.';

    public function __construct(
        private readonly string $mode = self::MODE_HTML5,
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer = $normalizer;
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\in_array($this->mode, self::EMAIL_MODES, true)) {
            throw new InvalidOptionException('mode', self::EMAIL_MODES);
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
        }

        if ($this->mode === self::MODE_STRICT) {
            $emailValidator = new EmailValidator();

            if (!$emailValidator->isValid($value, new NoRFCWarningsValidation())) {
                throw new EmailException(
                    message: $this->message,
                    parameters: [
                        'value' => $value,
                        'name' => $name,
                        'mode' => $this->mode
                    ]
                );
            }
        }
        else if (!\preg_match(self::EMAIL_PATTERNS[$this->mode], $value)) {
            throw new EmailException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'mode' => $this->mode
                ]
            );
        }
    }
}