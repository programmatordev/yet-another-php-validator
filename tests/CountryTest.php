<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\CountryException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Country;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class CountryTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $codeMessage = '/Invalid code "(.*)". Accepted values are: "(.*)"./';
        $typeMessage = '/Expected value of type "string", "(.*)" given./';

        yield 'invalid code' => [new Country('invalid'), 'PT', $codeMessage];
        yield 'invalid type' => [new Country(), 123, $typeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = CountryException::class;
        $message = '/The "(.*)" value is not a valid "(.*)" country code, "(.*)" given./';

        yield 'default' => [new Country(), 'PRT', $exception, $message];
        yield 'alpha2' => [new Country(code: 'alpha2'), 'PRT', $exception, $message];
        yield 'alpha3' => [new Country(code: 'alpha3'), 'PT', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'default' => [new Country(), 'PT'];
        yield 'alpha2' => [new Country(code: 'alpha2'), 'PT'];
        yield 'alpha2 lowercase' => [new Country(code: 'alpha2'), 'pt'];
        yield 'alpha3' => [new Country(code: 'alpha3'), 'PRT'];
        yield 'alpha3 lowercase' => [new Country(code: 'alpha3'), 'prt'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Country(
                message: 'The "{{ name }}" value "{{ value }}" is not a valid "{{ code }}" country code.'
            ),
            'invalid',
            'The "test" value "invalid" is not a valid "alpha2" country code.'
        ];
    }
}