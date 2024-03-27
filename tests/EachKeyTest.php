<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\EachKeyException;
use ProgrammatorDev\Validator\Rule\EachKey;
use ProgrammatorDev\Validator\Rule\GreaterThan;
use ProgrammatorDev\Validator\Rule\Type;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\Validator\Validator;

class EachKeyTest extends AbstractTest
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
            new EachKey(new Validator(new Type('string'))),
            'invalid',
            $unexpectedTypeMessage
        ];
        yield 'unexpected value propagation' => [
            new EachKey(new Validator(new GreaterThan(10))),
            ['key1' => 1],
            $unexpectedPropagationMessage
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EachKeyException::class;
        $message = '/Invalid key\: The (.*) key value should be of type (.*), (.*) given\./';

        yield 'invalid array element' => [
            new EachKey(new Validator(new Type('string'))),
            ['key1' => 1, 'key2' => 2, 1 => 3],
            $exception,
            $message
        ];
        yield 'invalid traversable element' => [
            new EachKey(new Validator(new Type('string'))),
            new \ArrayIterator(['key1' => 1, 'key2' => 2, 1 => 3]),
            $exception,
            $message
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'array element' => [
            new EachKey(new Validator(new Type('string'))),
            ['key1' => 1, 'key2' => 2, 'key3' => 3]
        ];
        yield 'traversable element' => [
            new EachKey(new Validator(new Type('string'))),
            new \ArrayIterator(['key1' => 1, 'key2' => 2, 'key3' => 3])
        ];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new EachKey(
                validator: new Validator(new Type('string')),
                message: 'The {{ name }} key {{ key }} is invalid.'
            ),
            ['key1' => 1, 'key2' => 2, 1 => 3],
            'The test key 1 is invalid.'
        ];
    }
}