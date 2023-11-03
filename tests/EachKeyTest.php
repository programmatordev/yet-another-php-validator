<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\EachKeyException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\EachKey;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Type;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class EachKeyTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        yield 'invalid value type' => [
            new EachKey(new Validator(new Type('string'))),
            'invalid',
            '/Expected value of type "(.*)", "(.*)" given./'
        ];
        yield 'unexpected value propagation' => [
            new EachKey(new Validator(new GreaterThan(10))),
            ['key1' => 1],
            '/Cannot compare a type "(.*)" with a type "(.*)"./'
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = EachKeyException::class;
        $message = '/Invalid key: The (.*) key value should be of type (.*), (.*) given./';

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