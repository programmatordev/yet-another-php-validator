<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\ChoiceException;
use ProgrammatorDev\Validator\Rule\Choice;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;

class ChoiceTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];

        $unexpectedMultipleMessage = '/Expected value of type "array", "(.*)" given\./';
        $unexpectedMinMaxMessage = '/Maximum value must be greater than or equal to minimum value\./';

        yield 'multiple not array' => [new Choice($constraints, true), 1, $unexpectedMultipleMessage];
        yield 'min greater than max constraint' => [new Choice($constraints, true, 3, 2), [1, 2], $unexpectedMinMaxMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];
        $exception = ChoiceException::class;

        $message = '/The (.*) value is not a valid choice\. Accepted values are\: (.*)\./';
        $multipleMessage = '/The (.*) value has one or more invalid choices\. Accepted values are\: (.*)\./';
        $maxMessage = '/The (.*) value must have at most (.*) choices\./';
        $minMessage = '/The (.*) value must have at least (.*) choices\./';

        yield 'invalid choice' => [new Choice($constraints), 10, $exception, $message];
        yield 'invalid choice type' => [new Choice($constraints), '1', $exception, $message];
        yield 'multiple invalid choice' => [new Choice($constraints, true), [10], $exception, $multipleMessage];
        yield 'multiple invalid choice type' => [new Choice($constraints, true), ['1'], $exception, $multipleMessage];
        yield 'multiple invalid choice with valid' => [new Choice($constraints, true), [1, 10], $exception, $multipleMessage];
        yield 'min constraint' => [new Choice($constraints, true, 2), [1], $exception, $minMessage];
        yield 'max constraint' => [new Choice($constraints, true, null, 2), [1, 2, 3], $exception, $maxMessage];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];

        yield 'valid choice' => [new Choice($constraints), 1];
        yield 'multiple valid choice' => [new Choice($constraints, true), [1]];
        yield 'multiple valid choices' => [new Choice($constraints, true), [1, 2, 3]];
        yield 'min constraint' => [new Choice($constraints, true, 2), [1, 2]];
        yield 'max constraint' => [new Choice($constraints, true, null, 2), [1, 2]];
        yield 'min and max constraint' => [new Choice($constraints, true, 2, 4), [1, 2, 3]];
        yield 'same min and max constraint' => [new Choice($constraints, true, 2, 2), [1, 2]];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];

        yield 'message' => [
            new Choice(
                constraints: $constraints,
                message: '{{ name }} | {{ value }} | {{ constraints }}'
            ),
            10,
            'test | 10 | [1, 2, 3, 4, 5]'
        ];
        yield 'multiple message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                multipleMessage: '{{ name }} | {{ value }} | {{ constraints }}'
            ),
            [10],
            'test | [10] | [1, 2, 3, 4, 5]'
        ];
        yield 'min message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                min: 2,
                minMessage: '{{ name }} | {{ value }} | {{ constraints }} | {{ min }} | {{ max }} | {{ numElements }}'
            ),
            [1],
            'test | [1] | [1, 2, 3, 4, 5] | 2 | null | 1'
        ];
        yield 'max message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                max: 2,
                maxMessage: '{{ name }} | {{ value }} | {{ constraints }} | {{ min }} | {{ max }} | {{ numElements }}'
            ),
            [1, 2, 3],
            'test | [1, 2, 3] | [1, 2, 3, 4, 5] | null | 2 | 3'
        ];
    }


}