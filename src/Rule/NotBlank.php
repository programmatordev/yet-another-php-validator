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

        $resolver->setDefaults([
            'message' => 'The "{{ name }}" value should not be blank, "{{ value }}" given.',
            'normalizer' => null
        ]);

        $resolver->setAllowedTypes('message', 'string');
        $resolver->setAllowedTypes('normalizer', ['null', 'callable']);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @throws NotBlankException
     */
    public function assert(mixed $value, string $name): void
    {
        // Keep original value for parameter
        $input = $value;

        // Call normalizer if provided
        if ($this->options['normalizer'] !== null) {
            $input = ($this->options['normalizer'])($input);
        }

        // Do not allow null, false, [] and ''
        if ($input === false || (empty($input) && $input != '0')) {
            throw new NotBlankException(
                message: $this->options['message'],
                parameters: [
                    'value' => $value,
                    'name' => $name
                ]
            );
        }
    }
}