<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\AllException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\UnexpectedValueException;
use ProgrammatorDev\YetAnotherPhpValidator\Exception\ValidationException;
use ProgrammatorDev\YetAnotherPhpValidator\Rule\Util\AssertIsValidatableTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class All extends AbstractRule implements RuleInterface
{
    use AssertIsValidatableTrait;

    private array $options;

    /**
     * @param RuleInterface[] $constraints
     */
    public function __construct(
        private readonly array $constraints,
        array $options = []
    )
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'At "{{ key }}": {{ message }}']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    public function assert(mixed $value, string $name): void
    {
        $this->assertIsValidatable($this->constraints);

        if (!\is_array($value)) {
            throw new UnexpectedValueException(
                \sprintf('Expected value of type "array", "%s" given', get_debug_type($value))
            );
        }

        try {
            foreach ($value as $key => $input) {
                foreach ($this->constraints as $constraint) {
                    $constraint->assert($input, $name);
                }
            }
        }
        catch (ValidationException $exception) {
            throw new AllException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name,
                    'key' => $key,
                    'message' => $exception->getMessage()
                ]
            );
        }
    }
}