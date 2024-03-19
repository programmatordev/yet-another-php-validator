<?php

namespace ProgrammatorDev\Validator\Test;

use ProgrammatorDev\Validator\Exception\RuleNotFoundException;
use ProgrammatorDev\Validator\Factory\Factory;
use ProgrammatorDev\Validator\Rule\RuleInterface;

class FactoryTest extends AbstractTest
{
    private Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory();
    }

    public function testFactoryCreateRuleFailure()
    {
        $this->expectException(RuleNotFoundException::class);
        $this->expectExceptionMessage('"invalidRule" rule does not exist.');
        $this->factory->createRule('invalidRule');
    }

    public function testFactoryCreateRuleSuccess()
    {
        $this->assertInstanceOf(RuleInterface::class, $this->factory->createRule('notBlank'));
    }
}