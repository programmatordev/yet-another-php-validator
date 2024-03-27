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
        $unexpectedCodeMessage = '/Invalid code "(.*)"\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", (.*) given\./';

        yield 'invalid code' => [new Country('invalid'), 'PT', $unexpectedCodeMessage];
        yield 'invalid type' => [new Country(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = CountryException::class;
        $message = '/The (.*) value is not a valid (.*) country code, (.*) given\./';

        yield 'default' => [new Country(), 'PRT', $exception, $message];
        yield 'alpha2' => [new Country(code: 'alpha-2'), 'PRT', $exception, $message];
        yield 'alpha3' => [new Country(code: 'alpha-3'), 'PT', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'default' => [new Country(), 'PT'];
        yield 'alpha2' => [new Country(code: 'alpha-2'), 'PT'];
        yield 'alpha2 lowercase' => [new Country(code: 'alpha-2'), 'pt'];
        yield 'alpha3' => [new Country(code: 'alpha-3'), 'PRT'];
        yield 'alpha3 lowercase' => [new Country(code: 'alpha-3'), 'prt'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Country(
                message: 'The {{ name }} value {{ value }} is not a valid {{ code }} country code.'
            ),
            'invalid',
            'The test value "invalid" is not a valid "alpha-2" country code.'
        ];
    }
}