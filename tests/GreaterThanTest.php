<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class GreaterThanTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $message = '/Cannot compare a type "(.*)" with a type "(.*)"/';

        yield 'datetime constraint with int value' => [new GreaterThan(new \DateTime()), 10, $message];
        yield 'datetime constraint with float value' => [new GreaterThan(new \DateTime()), 1.0, $message];
        yield 'datetime constraint with string value' => [new GreaterThan(new \DateTime()), 'a', $message];
        yield 'int constraint with string value' => [new GreaterThan(10), 'a', $message];
        yield 'float constraint with string value' => [new GreaterThan(1.0), 'a', $message];
        yield 'array constraint' => [new GreaterThan([10]), 10, $message];
        yield 'null constraint' => [new GreaterThan(null), 10, $message];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = GreaterThanException::class;
        $message = '/The (.*) value should be greater than (.*), (.*) given./';

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
                message: 'The {{ name }} value {{ value }} is not greater than {{ constraint }}.'
            ),
            1,
            'The test value 1 is not greater than 10.'
        ];
    }
}