<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\CollectionException;
use ProgrammatorDev\Validator\Rule\Collection;
use ProgrammatorDev\Validator\Test\Util\TestRuleFailureConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleMessageOptionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleSuccessConditionTrait;
use ProgrammatorDev\Validator\Test\Util\TestRuleUnexpectedValueTrait;
use ProgrammatorDev\Validator\Validator;

class CollectionTest extends AbstractTest
{
    use TestRuleUnexpectedValueTrait;
    use TestRuleFailureConditionTrait;
    use TestRuleSuccessConditionTrait;
    use TestRuleMessageOptionTrait;

    public static function provideRuleUnexpectedValueData(): \Generator
    {
        $unexpectedFieldValueMessage = '/At field (.*)\: (.*)\./';
        $unexpectedTypeMessage = '/Expected value of type "array\|\\\Traversable", "(.*)" given\./';

        yield 'invalid field value' => [
            new Collection(fields: ['field' => 'invalid']),
            ['field' => 'value'],
            $unexpectedFieldValueMessage
        ];
        yield 'invalid value type' => [
            new Collection(fields: ['field' => Validator::notBlank()]),
            'invalid',
            $unexpectedTypeMessage
        ];
    }

    public static function provideRuleFailureConditionData(): \Generator
    {
        $exception = CollectionException::class;
        $extraFieldsMessage = '/The (.*) field is not allowed\./';
        $missingFieldsMessage = '/The (.*) field is missing\./';

        yield 'invalid field' => [
            new Collection(fields: ['field' => Validator::notBlank()]),
            ['field' => ''],
            $exception,
            '/The "(.*)" value should not be blank, "" given\./'
        ];
        yield 'extra fields' => [
            new Collection(fields: ['field' => Validator::notBlank()]),
            ['field' => 'value', 'extrafield' => 'extravalue'],
            $exception,
            $extraFieldsMessage
        ];
        yield 'missing fields' => [
            new Collection(
                fields: [
                    'field1' => Validator::notBlank(),
                    'field2' => Validator::notBlank()
                ]
            ),
            ['field1' => 'value1'],
            $exception,
            $missingFieldsMessage
        ];
    }

    public static function provideRuleSuccessConditionData(): \Generator
    {
        yield 'array' => [
            new Collection(fields: ['field' => Validator::notBlank()]),
            ['field' => 'value'],
        ];
        yield 'traversable' => [
            new Collection(fields: ['field' => Validator::notBlank()]),
            new \ArrayIterator(['field' => 'value'])
        ];
        yield 'extra fields' => [
            new Collection(
                fields: ['field' => Validator::notBlank()],
                allowExtraFields: true
            ),
            ['field' => 'value', 'extrafield' => 'extravalue']
        ];
    }

    public static function provideRuleMessageOptionData(): \Generator
    {
        yield 'message' => [
            new Collection(
                fields: ['field' => Validator::notBlank()],
                message: 'There was an error: {{ message }}'
            ),
            ['field' => ''],
            'There was an error: The "field" value should not be blank, "" given.'
        ];
        yield 'extra fields message' => [
            new Collection(
                fields: ['field' => Validator::notBlank()],
                extraFieldsMessage: 'The {{ field }} was not expected.'
            ),
            ['field' => 'value', 'extrafield' => 'extravalue'],
            'The "extrafield" was not expected.'
        ];
        yield 'missing fields message' => [
            new Collection(
                fields: ['field' => Validator::notBlank()],
                missingFieldsMessage: 'Missing field: {{ field }}.'
            ),
            [],
            'Missing field: "field".'
        ];
    }
}