<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Test;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\ChoiceException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Choice;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\YetAnotherPhpValidator\Test\Util\TestRuleUnexpectedValueTrait;

class ChoiceTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];
        $multipleMessage = '/Expected value of type "array" when using multiple choices, "(.*)" given/';
        $constraintMessage = '/Max constraint value must be greater than or equal to min constraint value./';

        yield 'multiple not array' => [new Choice($constraints, true), 1, $multipleMessage];
        yield 'min greater than max constraint' => [new Choice($constraints, true, 3, 2), [1, 2], $constraintMessage];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $constraints = [1, 2, 3, 4, 5];
        $exception = ChoiceException::class;
        $message = '/The "(.*)" value is not a valid choice, "(.*)" given. Accepted values are: "(.*)"./';
        $multipleMessage = '/The "(.*)" value has one or more invalid choices, "(.*)" given. Accepted values are: "(.*)"./';
        $maxMessage = '/The "(.*)" value must have at most (.*) choices, (.*) choices given./';
        $minMessage = '/The "(.*)" value must have at least (.*) choices, (.*) choices given./';

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
                message: 'The "{{ name }}" value "{{ value }}" is not a valid choice.'
            ),
            10,
            'The "test" value "10" is not a valid choice.'
        ];
        yield 'multiple message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                multipleMessage: 'The "{{ name }}" value "{{ value }}" is not a valid choice.'
            ),
            [10],
            'The "test" value "[10]" is not a valid choice.'
        ];
        yield 'min message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                minConstraint: 2,
                minMessage: 'The "{{ name }}" value should have at least {{ minConstraint }} choices.'
            ),
            [1],
            'The "test" value should have at least 2 choices.'
        ];
        yield 'max message' => [
            new Choice(
                constraints: $constraints,
                multiple: true,
                maxConstraint: 2,
                maxMessage: 'The "{{ name }}" value should have at most {{ maxConstraint }} choices.'
            ),
            [1, 2, 3],
            'The "test" value should have at most 2 choices.'
        ];
    }


}