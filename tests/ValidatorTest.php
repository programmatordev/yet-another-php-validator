<?php

namespace ProgrammatorDev\Validator\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use ProgrammatorDev\Validator\Exception\UnexpectedValueException;
use ProgrammatorDev\Validator\Exception\ValidationException;
use ProgrammatorDev\Validator\Rule\GreaterThan;
use ProgrammatorDev\Validator\Rule\LessThan;
use ProgrammatorDev\Validator\Rule\NotBlank;
use ProgrammatorDev\Validator\Rule\RuleInterface;
use ProgrammatorDev\Validator\Validator;

class ValidatorTest extends AbstractTest
{
    public function testValidatorRequiredRules()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Validator rules not found: at least one rule is required.');

        $validator = new Validator();
        $validator->assert(true, 'test');
    }

    #[DataProvider('provideValidatorUsageApproachData')]
    public function testValidatorGetRules(Validator $validator)
    {
        $this->assertCount(3, $validator->getRules());
        $this->assertContainsOnlyInstancesOf(RuleInterface::class, $validator->getRules());
    }

    #[DataProvider('provideValidatorUsageApproachData')]
    public function testValidatorFailureCondition(Validator $validator)
    {
        $this->assertFalse($validator->validate(false));
        $this->expectException(ValidationException::class);
        $validator->assert(false, 'test');
    }

    #[DataProvider('provideValidatorUsageApproachData')]
    public function testValidatorSuccessCondition(Validator $validator)
    {
        $this->assertTrue($validator->validate(15));
        $validator->assert(15, 'test');
    }

    public static function provideValidatorUsageApproachData(): \Generator
    {
        yield 'fluent approach' => [
            Validator
                ::notBlank()
                ->greaterThan(10)
                ->lessThan(20)
        ];
        yield 'dependency injection approach' => [
            new Validator(
                new NotBlank(),
                new GreaterThan(10),
                new LessThan(20)
            )
        ];
        yield 'method approach' => [
            (new Validator)
                ->addRule(new NotBlank())
                ->addRule(new GreaterThan(10))
                ->addRule(new LessThan(20))
        ];
    }
}