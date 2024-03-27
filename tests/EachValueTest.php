<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\EachValueException;
use ProgrammatorDev\Validator\Rule\EachValue;
use ProgrammatorDev\Validator\Rule\GreaterThan;
use ProgrammatorDev\Validator\Rule\NotBlank;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\Validator\Validator;

class EachValueTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedTypeMessage = '/Expected value of type "(.*)", "(.*)" given\./';
        $unexpectedPropagationMessage = '/Cannot compare a type "(.*)" with a type "(.*)"\./';

        yield 'invalid value type' => [
            new EachValue(new Validator(new NotBlank())),
            'invalid',
            $unexpectedTypeMessage
        ];
        yield 'unexpected value propagation' => [
            new EachValue(new Validator(new GreaterThan(10))),
            ['a'],
            $unexpectedPropagationMessage
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EachValueException::class;
        $message = '/At key (.*)\: The (.*) value should not be blank, (.*) given\./';

        yield 'invalid array element' => [
            new EachValue(new Validator(new NotBlank())),
            [1, 2, ''],
            $exception,
            $message
        ];
        yield 'invalid traversable element' => [
            new EachValue(new Validator(new NotBlank())),
            new \ArrayIterator([1, 2, '']),
            $exception,
            $message
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'array element' => [
            new EachValue(new Validator(new NotBlank(), new GreaterThan(1))),
            [2, 3, 4]
        ];
        yield 'traversable element' => [
            new EachValue(new Validator(new NotBlank(), new GreaterThan(1))),
            new \ArrayIterator([2, 3, 4])
        ];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new EachValue(
                validator: new Validator(new NotBlank()),
                message: 'The {{ name }} value {{ value }} failed at key {{ key }}.'
            ),
            [1, 2, ''],
            'The test value [1, 2, ""] failed at key 2.'
        ];
    }
}