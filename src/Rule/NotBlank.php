<?php

namespace ProgrammatorDev\YetAnotherPhpValidator\Rule;

use ProgrammatorDev\YetAnotherPhpValidator\Exception\NotBlankException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotBlank extends AbstractRule implements RuleInterface
{
    private array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(['message' => 'The "{{ name }}" value should not be blank, "{{ value }}" given.']);

        $resolver->setAllowedTypes('message', 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, string $name): void
    {
        // Do not allow null, false, [] and ''
        if ($value === false || (empty($value) && $value != '0')) {
            throw new NotBlankException(
                message: $this->options['message'],
                parameters: [
                    'name' => $name,
                    'value' => $value
                ]
            );
        }
    }
}