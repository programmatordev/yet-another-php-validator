<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\GreaterThanException;
use ProgrammatorDev\Validator\Rule\GreaterThan;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class GreaterThanTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedComparableMessage = '/Cannot compare a type "(.*)" with a type "(.*)"\./';

        yield 'datetime constraint with int value' => [new GreaterThan(new \DateTime()), 10, $unexpectedComparableMessage];
        yield 'datetime constraint with float value' => [new GreaterThan(new \DateTime()), 1.0, $unexpectedComparableMessage];
        yield 'datetime constraint with string value' => [new GreaterThan(new \DateTime()), 'a', $unexpectedComparableMessage];
        yield 'int constraint with string value' => [new GreaterThan(10), 'a', $unexpectedComparableMessage];
        yield 'float constraint with string value' => [new GreaterThan(1.0), 'a', $unexpectedComparableMessage];
        yield 'array constraint' => [new GreaterThan([10]), 10, $unexpectedComparableMessage];
        yield 'null constraint' => [new GreaterThan(null), 10, $unexpectedComparableMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = GreaterThanException::class;
        $message = '/The (.*) value should be greater than (.*)\./';

        yield 'datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('yesterday'), $exception, $message];
        yield 'same datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('today'), $exception, $message];
        yield 'int' => [new GreaterThan(10), 1, $exception, $message];
        yield 'same int' => [new GreaterThan(10), 10, $exception, $message];
        yield 'float' => [new GreaterThan(10.0), 1.0, $exception, $message];
        yield 'same float' => [new GreaterThan(10.0), 10.0, $exception, $message];
        yield 'int with float' => [new GreaterThan(10), 1.0, $exception, $message];
        yield 'same int with float' => [new GreaterThan(10), 10.0, $exception, $message];
        yield 'string' => [new GreaterThan('z'), 'a', $exception, $message];
        yield 'same string' => [new GreaterThan('a'), 'a', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('tomorrow')];
        yield 'int' => [new GreaterThan(10), 20];
        yield 'float' => [new GreaterThan(10.0), 20.0];
        yield 'int with float' => [new GreaterThan(10), 20.0];
        yield 'string' => [new GreaterThan('a'), 'z'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new GreaterThan(
                constraint: 10,
                message: '{{ name }} | {{ value }} | {{ constraint }}'
            ),
            1,
            'test | 1 | 10'
        ];
    }
}