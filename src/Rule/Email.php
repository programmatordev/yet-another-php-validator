<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\NoRFCWarningsValidation;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\EmailException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedOptionException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedTypeException;

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

    private const PATTERN_HTML5 = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/';
    private const PATTERN_HTML5_ALLOW_NO_TLD = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/';

    private const EMAIL_PATTERNS = [
        self::MODE_HTML5 => self::PATTERN_HTML5,
        self::MODE_HTML5_ALLOW_NO_TLD => self::PATTERN_HTML5_ALLOW_NO_TLD
    ];

    // Using array to bypass unallowed callable type in properties
    private array $normalizer;

    public function __construct(
        private readonly string $mode = self::MODE_HTML5,
        ?callable $normalizer = null,
        private readonly string $message = 'The {{ name }} value is not a valid email address, {{ value }} given.'
    )
    {
        $this->normalizer['callable'] = $normalizer;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\in_array($this->mode, self::EMAIL_MODES, true)) {
            throw new UnexpectedOptionException('mode', self::EMAIL_MODES, $this->mode);
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException('string', get_debug_type($value));
        }

        if ($this->normalizer['callable'] !== null) {
            $value = ($this->normalizer['callable'])($value);
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