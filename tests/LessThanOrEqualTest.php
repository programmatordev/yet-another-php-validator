<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\LessThanOrEqualException;
use ProgrammatorDev\Validator\Rule\LessThanOrEqual;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class LessThanOrEqualTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Cannot compare a type "(.*)" with a type "(.*)"\./';

        yield 'datetime constraint with int value' => [new LessThanOrEqual(new \DateTime()), 10, $unexpectedTypeMessage];
        yield 'datetime constraint with float value' => [new LessThanOrEqual(new \DateTime()), 1.0, $unexpectedTypeMessage];
        yield 'datetime constraint with string value' => [new LessThanOrEqual(new \DateTime()), 'a', $unexpectedTypeMessage];
        yield 'int constraint with string value' => [new LessThanOrEqual(10), 'a', $unexpectedTypeMessage];
        yield 'float constraint with string value' => [new LessThanOrEqual(1.0), 'a', $unexpectedTypeMessage];
        yield 'array constraint' => [new LessThanOrEqual([10]), 10, $unexpectedTypeMessage];
        yield 'null constraint' => [new LessThanOrEqual(null), 10, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LessThanOrEqualException::class;
        $message = '/The (.*) value should be less than or equal to (.*)\./';

        yield 'datetime' => [new LessThanOrEqual(new \DateTime('today')), new \DateTime('tomorrow'), $exception, $message];
        yield 'int' => [new LessThanOrEqual(10), 20, $exception, $message];
        yield 'float' => [new LessThanOrEqual(10.0), 20.0, $exception, $message];
        yield 'int with float' => [new LessThanOrEqual(10), 20.0, $exception, $message];
        yield 'string' => [new LessThanOrEqual('a'), 'z', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new LessThanOrEqual(new \DateTime('today')), new \DateTime('yesterday')];
        yield 'same datetime' => [new LessThanOrEqual(new \DateTime('today')), new \DateTime('today')];
        yield 'int' => [new LessThanOrEqual(10), 1];
        yield 'same int' => [new LessThanOrEqual(10), 10];
        yield 'float' => [new LessThanOrEqual(10.0), 1.0];
        yield 'same float' => [new LessThanOrEqual(10.0), 10.0];
        yield 'int with float' => [new LessThanOrEqual(10), 1.0];
        yield 'same int with float' => [new LessThanOrEqual(10), 10.0];
        yield 'string' => [new LessThanOrEqual('z'), 'a'];
        yield 'same string' => [new LessThanOrEqual('a'), 'a'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new LessThanOrEqual(
                constraint: 10,
                message: '{{ name }} | {{ value }} | {{ constraint }}'
            ),
            20,
            'test | 20 | 10'
        ];
    }
}