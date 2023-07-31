<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\LessThanException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\LessThan;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;

class LessThanTest extends AbstractTest
{
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = LessThanException::class;
        $exceptionMessageInvalid = '/Cannot compare a type "(.*)" with a type "(.*)"/';
        $exceptionMessageFailure = '/The "(.*)" value should be less than "(.*)", "(.*)" given./';

        yield 'datetime constraint with int value' => [new LessThan(new \DateTime()), 10, $exception, $exceptionMessageInvalid];
        yield 'datetime constraint with float value' => [new LessThan(new \DateTime()), 1.0, $exception, $exceptionMessageInvalid];
        yield 'datetime constraint with string value' => [new LessThan(new \DateTime()), 'a', $exception, $exceptionMessageInvalid];
        yield 'int constraint with string value' => [new LessThan(10), 'a', $exception, $exceptionMessageInvalid];
        yield 'float constraint with string value' => [new LessThan(1.0), 'a', $exception, $exceptionMessageInvalid];
        yield 'array constraint' => [new LessThan([10]), 10, $exception, $exceptionMessageInvalid];
        yield 'null constraint' => [new LessThan(null), 10, $exception, $exceptionMessageInvalid];

        yield 'datetime' => [new LessThan(new \DateTime('today')), new \DateTime('tomorrow'), $exception, $exceptionMessageFailure];
        yield 'same datetime' => [new LessThan(new \DateTime('today')), new \DateTime('today'), $exception, $exceptionMessageFailure];
        yield 'int' => [new LessThan(10), 20, $exception, $exceptionMessageFailure];
        yield 'same int' => [new LessThan(10), 10, $exception, $exceptionMessageFailure];
        yield 'float' => [new LessThan(10.0), 20.0, $exception, $exceptionMessageFailure];
        yield 'same float' => [new LessThan(10.0), 10.0, $exception, $exceptionMessageFailure];
        yield 'int with float' => [new LessThan(10), 20.0, $exception, $exceptionMessageFailure];
        yield 'same int with float' => [new LessThan(10), 10.0, $exception, $exceptionMessageFailure];
        yield 'string' => [new LessThan('a'), 'z', $exception, $exceptionMessageFailure];
        yield 'same string' => [new LessThan('a'), 'a', $exception, $exceptionMessageFailure];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'datetime' => [new LessThan(new \DateTime('today')), new \DateTime('yesterday')];
        yield 'int' => [new LessThan(10), 1];
        yield 'float' => [new LessThan(10.0), 1.0];
        yield 'int with float' => [new LessThan(10), 1.0];
        yield 'string' => [new LessThan('z'), 'a'];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new LessThan(10, [
                'message' => 'The "{{ name }}" value "{{ value }}" is not less than "{{ constraint }}".'
            ]),
            20,
            'The "test" value "20" is not less than "10".'
        ];
    }
}