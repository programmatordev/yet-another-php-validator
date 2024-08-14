<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\LanguageException;
use ProgrammatorDev\Validator\Rule\Language;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class LanguageTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "code" option is not valid\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", (.*) given\./';

        yield 'invalid option code' => [new Language('invalid'), 'pt', $invalidOptionMessage];
        yield 'unexpected type' => [new Language(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LanguageException::class;
        $message = '/The (.*) value is not a valid language\./';

        yield 'default' => [new Language(), 'prt', $exception, $message];
        yield 'alpha2' => [new Language(code: 'alpha-2'), 'por', $exception, $message];
        yield 'alpha3' => [new Language(code: 'alpha-3'), 'pt', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'default' => [new Language(), 'pt'];
        yield 'alpha2' => [new Language(code: 'alpha-2'), 'pt'];
        yield 'alpha3' => [new Language(code: 'alpha-3'), 'por'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Language(
                message: '{{ name }} | {{ value }} | {{ code }}'
            ),
            'invalid',
            'test | "invalid" | "alpha-2"'
        ];
    }
}