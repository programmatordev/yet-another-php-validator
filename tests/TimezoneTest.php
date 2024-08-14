<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\TimezoneException;
use ProgrammatorDev\Validator\Rule\Timezone;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class TimezoneTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $optionDefinitionMessage = '/The "countryCode" option should be specified when the "timezoneGroup" is "\\\DateTimeZone::PER_COUNTRY"\./';
        $invalidOptionMessage = '/The "countryCode" option is not valid\./';

        yield 'option definition country code' => [
            new Timezone(\DateTimeZone::PER_COUNTRY),
            'Europe/Lisbon',
            $optionDefinitionMessage
        ];
        yield 'invalid option country code' => [
            new Timezone(\DateTimeZone::PER_COUNTRY, 'prt'),
            'Europe/Lisbon',
            $invalidOptionMessage
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = TimezoneException::class;
        $message = '/The (.*) value is not a valid timezone\./';

        yield 'invalid timezone' => [new Timezone(), 'Invalid/Timezone', $exception, $message];
        yield 'not of timezone group' => [new Timezone(\DateTimeZone::AFRICA), 'Europe/Lisbon', $exception, $message];
        yield 'not of timezone country' => [new Timezone(\DateTimeZone::PER_COUNTRY, 'es'), 'Europe/Lisbon', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'valid timezone' => [new Timezone(), 'Europe/Lisbon'];
        yield 'is of timezone group' => [new Timezone(\DateTimeZone::EUROPE), 'Europe/Lisbon'];
        yield 'is of multiple timezone groups' => [new Timezone(\DateTimeZone::EUROPE | \DateTimeZone::UTC), 'UTC'];
        yield 'is of timezone country' => [new Timezone(\DateTimeZone::PER_COUNTRY, 'pt'), 'Europe/Lisbon'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Timezone(
                message: '{{ name }} | {{ value }} | {{ countryCode }}'
            ),
            'Invalid/Timezone',
            'test | "Invalid/Timezone" | null'
        ];
    }

}