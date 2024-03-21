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
        $unexpectedMissingMinMaxMessage = '/At least one of the options "min" or "max" must be given./';
        $unexpectedMinMaxMessage = '/Maximum value must be greater than or equal to minimum value./';
        $unexpectedTypeMessage = '/Expected value of type "array|\Countable", "(.*)" given./';

        yield 'missing min max' => [new Count(), [1, 2, 3], $unexpectedMissingMinMaxMessage];
        yield 'min greater than max constraint' => [new Count(min: 3, max: 2), [1, 2, 3], $unexpectedMinMaxMessage];
        yield 'invalid type value' => [new Count(min: 5, max: 10), 1, $unexpectedTypeMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $value = [1, 2, 3, 4, 5];
        $exception = CountException::class;

        $minMessage = '/The (.*) value should contain (.*) elements or more, (.*) elements given./';
        $maxMessage = '/The (.*) value should contain (.*) elements or less, (.*) elements given./';
        $exactMessage = '/The (.*) value should contain exactly (.*) elements, (.*) elements given./';

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
                minMessage: 'The {{ name }} value should have at least {{ min }} elements.'
            ),
            $value,
            'The test value should have at least 10 elements.'
        ];
        yield 'max message' => [
            new Count(
                max: 2,
                maxMessage: 'The {{ name }} value should have at most {{ max }} elements.'
            ),
            $value,
            'The test value should have at most 2 elements.'
        ];
        yield 'exact message' => [
            new Count(
                min: 2,
                max: 2,
                exactMessage: 'The {{ name }} value should have exactly {{ min }} elements.'
            ),
            $value,
            'The test value should have exactly 2 elements.'
        ];
    }
}