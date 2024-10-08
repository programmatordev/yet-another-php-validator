<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\PasswordStrengthException;
use ProgrammatorDev\Validator\Rule\PasswordStrength;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class PasswordStrengthTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $invalidOptionMessage = '/The "minStrength" option is not valid\. Accepted values are\: "(.*)"\./';
        $unexpectedTypeMessage = '/Expected value of type "string", "(.*)" given\./';

        yield 'invalid option min strength' => [new PasswordStrength(minStrength: 'invalid'), 'password', $invalidOptionMessage];
        yield 'unexpected type' => [new PasswordStrength(), 123, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = 'password';
        $exception = PasswordStrengthException::class;
        $message = '/The password strength is not strong enough\./';

        yield 'min strength weak' => [new PasswordStrength(minStrength: 'weak'), $value, $exception, $message];
        yield 'min strength medium' => [new PasswordStrength(minStrength: 'medium'), $value, $exception, $message];
        yield 'min strength strong' => [new PasswordStrength(minStrength: 'strong'), $value, $exception, $message];
        yield 'min strength very strong' => [new PasswordStrength(minStrength: 'very-strong'), $value, $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        $value = 'tP}D+9_$?m&g<ZX[D-]}5`ou$}Y,G1';

        yield 'min strength weak' => [new PasswordStrength(minStrength: 'weak'), $value];
        yield 'min strength medium' => [new PasswordStrength(minStrength: 'medium'), $value];
        yield 'min strength strong' => [new PasswordStrength(minStrength: 'strong'), $value];
        yield 'min strength very strong' => [new PasswordStrength(minStrength: 'very-strong'), $value];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new PasswordStrength(
                message: '{{ name }} | {{ minStrength }}'
            ),
            'password',
            'test | "medium"'
        ];
    }
}