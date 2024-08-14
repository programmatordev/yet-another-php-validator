<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\EmailException;
use ProgrammatorDev\Validator\Rule\Email;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class EmailTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "mode" option is not valid\. Accepted values are\: "(.*)"./';
        $unexpectedTypeMessage = '/Expected value of type "string", "(.*)" given\./';

        yield 'invalid option mode' => [new Email('invalid'), 'test@example.com', $invalidOptionMessage];
        yield 'unexpected type' => [new Email(), 1, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EmailException::class;
        $message = '/The (.*) value is not a valid email address\./';

        yield 'html5' => [new Email('html5'), 'invalid', $exception, $message];
        yield 'html5 without tld' => [new Email('html5'), 'test@example', $exception, $message];
        yield 'html5-allow-no-tld' => [new Email('html5-allow-no-tld'), 'invalid', $exception, $message];
        yield 'strict' => [new Email('strict'), 'invalid', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'html5' => [new Email('html5'), 'test@example.com'];
        yield 'html5-allow-no-tld' => [new Email('html5-allow-no-tld'), 'test@example.com'];
        yield 'html5-allow-no-tld without tld' => [new Email('html5-allow-no-tld'), 'test@example'];
        yield 'strict' => [new Email('strict'), 'test@example.com'];
        yield 'normalizer' => [new Email(normalizer: 'trim'), 'test@example.com '];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Email(
                message: '{{ name }} | {{ value }} | {{ mode }}'
            ),
            'invalid',
            'test | "invalid" | "html5"'
        ];
    }
}