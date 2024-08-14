<?php

namespace ProgrammatorDev\Validator\Rule;

use ProgrammatorDev\Validator\Exception\UnexpectedTypeException;
use ProgrammatorDev\Validator\Exception\UrlException;

class Url extends AbstractRule implements RuleInterface
{
    // https://github.com/symfony/validator/blob/7.0/Constraints/UrlValidator.php
    private const PATTERN = '~^
        (%s)://   # protocol
        (((?:[\_\.\pL\pN-]|%%[0-9A-Fa-f]{2})+:)?((?:[\_\.\pL\pN-]|%%[0-9A-Fa-f]{2})+)@)?   # basic auth
        (
            (?:
                (?:xn--[a-z0-9-]++\.)*+xn--[a-z0-9-]++      # a domain name using punycode
                    |
                (?:[\pL\pN\pS\pM\-\_]++\.)+[\pL\pN\pM]++    # a multi-level domain name
                    |
                [a-z0-9\-\_]++                              # a single-level domain name
            )\.?
                |                                           # or
            \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}              # an IP address
                |                                           # or
            \[
                (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
            \]  # an IPv6 address
        )
        (:[0-9]+)?                                                          # a port (optional)
        (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*          # a path
        (?:\? (?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a query (optional)
        (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?       # a fragment (optional)
    $~ixu';

    /** @var ?callable */
    private $normalizer;
    private string $message = 'The {{ name }} value is not a valid URL address.';

    public function __construct(
        private readonly array $protocols = ['http', 'https'],
        private readonly bool $allowRelativeProtocol = false,
        ?callable $normalizer = null,
        ?string $message = null
    )
    {
        $this->normalizer = $normalizer;
        $this->message = $message ?? $this->message;
    }

    public function assert(mixed $value, ?string $name = null): void
    {
        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ($this->normalizer !== null) {
            $value = ($this->normalizer)($value);
        }

        $pattern = $this->allowRelativeProtocol ? \str_replace('(%s):', '(?:(%s):)?', self::PATTERN) : self::PATTERN;
        $pattern = \sprintf($pattern, \implode('|', $this->protocols));

        if (!\preg_match($pattern, $value)) {
            throw new UrlException(
                message: $this->message,
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'protocols' => $this->protocols
                ]
            );
        }
    }
}