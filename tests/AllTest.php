<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\AllException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\All;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\GreaterThan;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\NotBlank;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Validator;

class AllTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        yield 'invalid constraint' => [
            new All([new NotBlank(), 'invalid']),
            [1, 2, 3],
            '/Expected constraint of type "RuleInterface", "(.*)" given./'
        ];
        yield 'invalid value type' => [
            new All([new NotBlank()]),
            'invalid',
            '/Expected value of type "array", "(.*)" given./'
        ];
        yield 'unexpected value propagation' => [
            new All([new GreaterThan(10)]),
            ['a'],
            '/Cannot compare a type "(.*)" with a type "(.*)"./'
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = AllException::class;
        $message = '/At "(.*)": The "(.*)" value should not be blank, "(.*)" given./';

        yield 'constraint' => [
            new All([new NotBlank()]),
            [1, 2, ''],
            $exception,
            $message
        ];
        yield 'validator' => [
            new All([(new Validator(new NotBlank()))]),
            [1, 2, ''],
            $exception,
            $message
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'constraints' => [
            new All([new NotBlank(), new GreaterThan(1)]),
            [2, 3, 4]
        ];
        yield 'validators' => [
            new All([
                (new Validator(new NotBlank())),
                (new Validator(new GreaterThan(1)))
            ]),
            [2, 3, 4]
        ];
        yield 'constraints and validators' => [
            new All([
                new NotBlank(),
                (new Validator(new GreaterThan(1)))
            ]),
            [2, 3, 4]
        ];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'constraint' => [
            new All(
                constraints: [new NotBlank()],
                options: [
                    'message' => 'The "{{ name }}" value "{{ value }}" failed at key "{{ key }}".'
                ]
            ), [1, 2, ''], 'The "test" value "[1, 2, ]" failed at key "2".'
        ];
    }
}