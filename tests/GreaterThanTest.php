<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\GreaterThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;

class GreaterThanTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = GreaterThanException::class;
        $exceptionMessageInvalid = '/Cannot compare a type "(.*)" with a type "(.*)"/';
        $exceptionMessageFailure = '/The "(.*)" value should be greater than "(.*)", "(.*)" given./';

        yield 'datetime constraint with int value' => [new GreaterThan(new \DateTime()), 10, $exception, $exceptionMessageInvalid];
        yield 'datetime constraint with float value' => [new GreaterThan(new \DateTime()), 1.0, $exception, $exceptionMessageInvalid];
        yield 'datetime constraint with string value' => [new GreaterThan(new \DateTime()), 'a', $exception, $exceptionMessageInvalid];
        yield 'int constraint with string value' => [new GreaterThan(10), 'a', $exception, $exceptionMessageInvalid];
        yield 'float constraint with string value' => [new GreaterThan(1.0), 'a', $exception, $exceptionMessageInvalid];
        yield 'array constraint' => [new GreaterThan([10]), 10, $exception, $exceptionMessageInvalid];
        yield 'null constraint' => [new GreaterThan(null), 10, $exception, $exceptionMessageInvalid];

        yield 'datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('yesterday'), $exception, $exceptionMessageFailure];
        yield 'same datetime' => [new GreaterThan(new \DateTime('today')), new \DateTime('today'), $exception, $exceptionMessageFailure];
        yield 'int' => [new GreaterThan(10), 1, $exception, $exceptionMessageFailure];
        yield 'same int' => [new GreaterThan(10), 10, $exception, $exceptionMessageFailure];
        yield 'float' => [new GreaterThan(10.0), 1.0, $exception, $exceptionMessageFailure];
        yield 'same float' => [new GreaterThan(10.0), 10.0, $exception, $exceptionMessageFailure];
        yield 'int with float' => [new GreaterThan(10), 1.0, $exception, $exceptionMessageFailure];
        yield 'same int with float' => [new GreaterThan(10), 10.0, $exception, $exceptionMessageFailure];
        yield 'string' => [new GreaterThan('z'), 'a', $exception, $exceptionMessageFailure];
        yield 'same string' => [new GreaterThan('a'), 'a', $exception, $exceptionMessageFailure];
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
            new GreaterThan(10, [
                'message' => 'The "{{ name }}" value "{{ value }}" is not greater than "{{ constraint }}".'
            ]),
            1,
            'The "test" value "1" is not greater than "10".'
        ];
    }
}