<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\RangeException;
use ProgrammatorDev\Validator\Rule\Range;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class RangeTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedComparableMessage = '/Cannot compare a type "(.*)" with a type "(.*)"\./';

        yield 'datetime constraint with int constraint' => [new Range(new \DateTime(), 10), new \DateTime(), $unexpectedComparableMessage];
        yield 'datetime constraint with float constraint' => [new Range(new \DateTime(), 10.0), new \DateTime(), $unexpectedComparableMessage];
        yield 'datetime constraint with string constraint' => [new Range(new \DateTime(), 'a'), new \DateTime(), $unexpectedComparableMessage];
        yield 'int constraint with string constraint' => [new Range(10, 'a'), 10, $unexpectedComparableMessage];
        yield 'float constraint with string constraint' => [new Range(1.0, 'a'), 1.0, $unexpectedComparableMessage];
        yield 'array constraint' => [new Range([10], 10), 10, $unexpectedComparableMessage];
        yield 'null constraint' => [new Range(null, 10), 10, $unexpectedComparableMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = RangeException::class;
        $message = '/The (.*) value should be between (.*) and (.*)\./';

        yield 'min datetime' => [new Range(new \DateTime('today'), new \DateTime('tomorrow')), new \DateTime('yesterday'), $exception, $message];
        yield 'min int' => [new Range(10, 20), 1, $exception, $message];
        yield 'min float' => [new Range(10.0, 20.0), 1.0, $exception, $message];
        yield 'min int with float' => [new Range(10, 20), 1.0, $exception, $message];
        yield 'min string' => [new Range('b', 'z'), 'a', $exception, $message];
        yield 'max datetime' => [new Range(new \DateTime('today'), new \DateTime('tomorrow')), new \DateTime('+2 days'), $exception, $message];
        yield 'max int' => [new Range(10, 20), 30, $exception, $message];
        yield 'max float' => [new Range(10.0, 20.0), 30.0, $exception, $message];
        yield 'max int with float' => [new Range(10, 20), 30.0, $exception, $message];
        yield 'max string' => [new Range('a', 'y'), 'z', $exception, $message];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new Range(new \DateTime('today'), new \DateTime('tomorrow')), new \DateTime('+1 hour')];
        yield 'int' => [new Range(10, 20), 15];
        yield 'float' => [new Range(10.0, 20.0), 15.0];
        yield 'int with float' => [new Range(10, 20), 15.0];
        yield 'string' => [new Range('a', 'z'), 'b'];
        yield 'min datetime' => [new Range(new \DateTime('today'), new \DateTime('tomorrow')), new \DateTime('today')];
        yield 'min int' => [new Range(10, 20), 10];
        yield 'min float' => [new Range(10.0, 20.0), 10.0];
        yield 'min int with float' => [new Range(10, 20), 10.0];
        yield 'min string' => [new Range('a', 'z'), 'a'];
        yield 'max datetime' => [new Range(new \DateTime('today'), new \DateTime('tomorrow')), new \DateTime('tomorrow')];
        yield 'max int' => [new Range(10, 20), 20];
        yield 'max float' => [new Range(10.0, 20.0), 20.0];
        yield 'max int with float' => [new Range(10, 20), 20.0];
        yield 'max string' => [new Range('a', 'z'), 'z'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Range(
                min: 10,
                max: 20,
                message: '{{ name }} | {{ value }} | {{ min }} | {{ max }}'
            ),
            30,
            'test | 30 | 10 | 20'
        ];
    }
}