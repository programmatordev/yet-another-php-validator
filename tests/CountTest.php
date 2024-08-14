<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\CountException;
use ProgrammatorDev\Validator\Rule\Count;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class CountTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $optionDefinitionMessage = '/At least one of the "min" or "max" options must be specified\./';
        $unexpectedTypeMessage = '/Expected value of type "array\|\\\Countable", "(.*)" given\./';

        yield 'option definition min max' => [new Count(), [1, 2, 3], $optionDefinitionMessage];
        yield 'unexpected type' => [new Count(min: 5, max: 10), 1, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = [1, 2, 3, 4, 5];
        $exception = CountException::class;

        $minMessage = '/The (.*) value should contain (.*) elements or more\./';
        $maxMessage = '/The (.*) value should contain (.*) elements or less\./';
        $exactMessage = '/The (.*) value should contain exactly (.*) elements\./';

        yield 'min constraint' => [new Count(min: 10), $value, $exception, $minMessage];
        yield 'max constraint' => [new Count(max: 2), $value, $exception, $maxMessage];
        yield 'min and max constraint' => [new Count(min: 10, max: 20), $value, $exception, $minMessage];
        yield 'exact constraint' => [new Count(min: 2, max: 2), $value, $exception, $exactMessage];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        $value = [1, 2, 3, 4, 5];

        yield 'min constraint' => [new Count(min: 5), $value];
        yield 'max constraint' => [new Count(max: 5), $value];
        yield 'min and max constraint' => [new Count(min: 4, max: 6), $value];
        yield 'exact constraint' => [new Count(min: 5, max: 5), $value];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        $value = [1, 2, 3, 4, 5];

        yield 'min message' => [
            new Count(
                min: 10,
                minMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numElements }}'
            ),
            $value,
            'test | [1, 2, 3, 4, 5] | 10 | null | 5'
        ];
        yield 'max message' => [
            new Count(
                max: 2,
                maxMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numElements }}'
            ),
            $value,
            'test | [1, 2, 3, 4, 5] | null | 2 | 5'
        ];
        yield 'exact message' => [
            new Count(
                min: 2,
                max: 2,
                exactMessage: '{{ name }} | {{ value }} | {{ min }} | {{ max }} | {{ numElements }}'
            ),
            $value,
            'test | [1, 2, 3, 4, 5] | 2 | 2 | 5'
        ];
    }
}