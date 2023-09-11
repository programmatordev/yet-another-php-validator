<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\EachValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\EachValue;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\NotBlank;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class EachValueTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        yield 'invalid value type' => [
            new EachValue(new Validator(new NotBlank())),
            'invalid',
            '/Expected value of type "(.*)", "(.*)" given./'
        ];
        yield 'unexpected value propagation' => [
            new EachValue(new Validator(new GreaterThan(10))),
            ['a'],
            '/Cannot compare a type "(.*)" with a type "(.*)"./'
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EachValueException::class;
        $message = '/At key "(.*)": The "(.*)" value should not be blank, "(.*)" given./';

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
                message: 'The "{{ name }}" value "{{ value }}" failed at key "{{ key }}".'
            ),
            [1, 2, ''],
            'The "test" value "[1, 2, ]" failed at key "2".'
        ];
    }
}