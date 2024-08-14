<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\CountryException;
use ProgrammatorDev\Validator\Rule\Country;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class CountryTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "code" option is not valid\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", (.*) given\./';

        yield 'invalid option code' => [new Country('invalid'), 'pt', $invalidOptionMessage];
        yield 'unexpected type' => [new Country(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = CountryException::class;
        $message = '/The (.*) value is not a valid country\./';

        yield 'default' => [new Country(), 'prt', $exception, $message];
        yield 'alpha2' => [new Country(code: 'alpha-2'), 'prt', $exception, $message];
        yield 'alpha3' => [new Country(code: 'alpha-3'), 'pt', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'default' => [new Country(), 'pt'];
        yield 'alpha2' => [new Country(code: 'alpha-2'), 'pt'];
        yield 'alpha3' => [new Country(code: 'alpha-3'), 'prt'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Country(
                message: '{{ name }} | {{ value }} | {{ code }}'
            ),
            'invalid',
            'test | "invalid" | "alpha-2"'
        ];
    }
}