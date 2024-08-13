<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\GreaterThanOrEqualException;
use ProgrammatorDev\Validator\Rule\GreaterThanOrEqual;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class GreaterThanOrEqualTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Cannot compare a type "(.*)" with a type "(.*)"\./';

        yield 'datetime constraint with int value' => [new GreaterThanOrEqual(new \DateTime()), 10, $unexpectedTypeMessage];
        yield 'datetime constraint with float value' => [new GreaterThanOrEqual(new \DateTime()), 1.0, $unexpectedTypeMessage];
        yield 'datetime constraint with string value' => [new GreaterThanOrEqual(new \DateTime()), 'a', $unexpectedTypeMessage];
        yield 'int constraint with string value' => [new GreaterThanOrEqual(10), 'a', $unexpectedTypeMessage];
        yield 'float constraint with string value' => [new GreaterThanOrEqual(1.0), 'a', $unexpectedTypeMessage];
        yield 'array constraint' => [new GreaterThanOrEqual([10]), 10, $unexpectedTypeMessage];
        yield 'null constraint' => [new GreaterThanOrEqual(null), 10, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = GreaterThanOrEqualException::class;
        $message = '/The (.*) value should be greater than or equal to (.*)\./';

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
                message: '{{ name }} | {{ value }} | {{ constraint }}'
            ),
            1,
            'test | 1 | 10'
        ];
    }
}