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
        $unexpectedCodeMessage = '/Invalid code "(.*)"\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", (.*) given\./';

        yield 'invalid code' => [new Language('invalid'), 'pt', $unexpectedCodeMessage];
        yield 'invalid type' => [new Language(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LanguageException::class;
        $message = '/The (.*) value is not a valid language, (.*) given\./';

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
                message: 'The {{ name }} value {{ value }} is not a valid {{ code }} language code.'
            ),
            'invalid',
            'The test value "invalid" is not a valid "alpha-2" language code.'
        ];
    }
}