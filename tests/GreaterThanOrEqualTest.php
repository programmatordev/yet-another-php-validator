<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanOrEqualException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThanOrEqual;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class GreaterThanOrEqualTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $message = '/Cannot compare a type "(.*)" with a type "(.*)"/';

        yield 'datetime constraint with int value' => [new GreaterThanOrEqual(new \DateTime()), 10, $message];
        yield 'datetime constraint with float value' => [new GreaterThanOrEqual(new \DateTime()), 1.0, $message];
        yield 'datetime constraint with string value' => [new GreaterThanOrEqual(new \DateTime()), 'a', $message];
        yield 'int constraint with string value' => [new GreaterThanOrEqual(10), 'a', $message];
        yield 'float constraint with string value' => [new GreaterThanOrEqual(1.0), 'a', $message];
        yield 'array constraint' => [new GreaterThanOrEqual([10]), 10, $message];
        yield 'null constraint' => [new GreaterThanOrEqual(null), 10, $message];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = GreaterThanOrEqualException::class;
        $message = '/The "(.*)" value should be greater than or equal to "(.*)", "(.*)" given./';

        yield 'datetime' => [new GreaterThanOrEqual(new \DateTime('today')), new \DateTime('yesterday'), $exception, $message];
        yield 'int' => [new GreaterThanOrEqual(10), 1, $exception, $message];
        yield 'float' => [new GreaterThanOrEqual(10.0), 1.0, $exception, $message];
        yield 'int with float' => [new GreaterThanOrEqual(10), 1.0, $exception, $message];
        yield 'string' => [new GreaterThanOrEqual('z'), 'a', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new GreaterThanOrEqual(new \DateTime('today')), new \DateTime('tomorrow')];
        yield 'same datetime' => [new GreaterThanOrEqual(new \DateTime('today')), new \DateTime('today')];
        yield 'int' => [new GreaterThanOrEqual(10), 20];
        yield 'same int' => [new GreaterThanOrEqual(10), 10];
        yield 'float' => [new GreaterThanOrEqual(10.0), 20.0];
        yield 'same float' => [new GreaterThanOrEqual(10.0), 10.0];
        yield 'int with float' => [new GreaterThanOrEqual(10), 20.0];
        yield 'same int with float' => [new GreaterThanOrEqual(10), 10.0];
        yield 'string' => [new GreaterThanOrEqual('a'), 'z'];
        yield 'same string' => [new GreaterThanOrEqual('a'), 'a'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new GreaterThanOrEqual(
                constraint: 10,
                options: [
                    'message' => 'The "{{ name }}" value "{{ value }}" is not greater than or equal to "{{ constraint }}".'
                ]
            ), 1, 'The "test" value "1" is not greater than or equal to "10".'
        ];
    }
}