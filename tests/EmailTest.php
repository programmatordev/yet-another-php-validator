<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\EmailException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Email;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class EmailTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $optionMessage = '/Invalid (.*) "(.*)". Accepted values are: "(.*)"./';
        $typeMessage = '/Expected value of type "string", "(.*)" given./';

        yield 'invalid option' => [new Email('invalid'), 'test@example.com', $optionMessage];
        yield 'invalid type' => [new Email(), 1, $typeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EmailException::class;
        $message = '/The (.*) value is not a valid email address, (.*) given./';

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
                message: 'The {{ name }} value {{ value }} in {{ mode }} mode is not a valid email address.'
            ),
            'invalid',
            'The test value "invalid" in "html5" mode is not a valid email address.'
        ];
    }
}