<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\RangeException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Range;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class RangeTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $comparableMessage = '/Cannot compare a type "(.*)" with a type "(.*)"/';
        $constraintMessage = '/Max constraint value must be greater than min constraint value./';

        yield 'datetime constraint with int constraint' => [new Range(new \DateTime(), 10), new \DateTime(), $comparableMessage];
        yield 'datetime constraint with float constraint' => [new Range(new \DateTime(), 10.0), new \DateTime(), $comparableMessage];
        yield 'datetime constraint with string constraint' => [new Range(new \DateTime(), 'a'), new \DateTime(), $comparableMessage];
        yield 'int constraint with string constraint' => [new Range(10, 'a'), 10, $comparableMessage];
        yield 'float constraint with string constraint' => [new Range(1.0, 'a'), 1.0, $comparableMessage];
        yield 'array constraint' => [new Range([10], 10), 10, $comparableMessage];
        yield 'null constraint' => [new Range(null, 10), 10, $comparableMessage];

        yield 'min greater than max constraint' => [new Range(10, 9), 10, $constraintMessage];
        yield 'same min and max constraint' => [new Range(10, 10), 10, $constraintMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = RangeException::class;
        $message = '/The "(.*)" value should be between "(.*)" and "(.*)", "(.*)" given./';

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
                minConstraint: 10,
                maxConstraint: 20,
                options: [
                    'message' => 'The "{{ name }}" value "{{ value }}" should be between "{{ minConstraint }}" and "{{ maxConstraint }}".'
                ]
            ), 30, 'The "test" value "30" should be between "10" and "20".'
        ];
    }
}