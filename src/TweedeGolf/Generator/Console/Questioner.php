<?php

namespace TweedeGolf\Generator\Console;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ValidatorInterface;
use TweedeGolf\Generator\Console\Input\InputTypeInterface;
use TweedeGolf\Generator\Console\Input\Registry\InputTypeRegistryInterface;
use TweedeGolf\Generator\Exception\GeneratorException;
use TweedeGolf\Generator\Exception\InputTypeNotFoundException;
use TweedeGolf\Generator\Input\Arguments;

class Questioner
{
    /**
     * @var InputTypeRegistryInterface
     */
    private $types;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var HelperSet
     */
    private $helperSet;

    /**
     * @var array
     */
    private $constraints;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        InputTypeRegistryInterface $types,
        OutputInterface $output,
        HelperSet $helperSet,
        array $constraints,
        ValidatorInterface $validator
    ) {
        $this->types = $types;
        $this->output = $output;
        $this->helperSet = $helperSet;
        $this->constraints = $constraints;
        $this->validator = $validator;
    }

    /**
     * @param string|InputTypeInterface $type
     * @param array                     $options
     * @param Constraint[]|Constraint   $constraints
     * @return mixed
     * @throws InputTypeNotFoundException
     */
    public function ask($type, array $options = [])
    {
        if (is_string($type)) {
            $type = $this->types->getType($type);
        }

        if (!($type instanceof InputTypeInterface)) {
            throw new InputTypeNotFoundException("Type should be an instance of InputTypeInterface or a string");
        }

        $options = $this->validateAndUpdateOptions($options, $type);

        $value = $options['default'];
        while (true) {
            $value = $type->ask($options, $this);
            if (is_callable($options['modify'])) {
                $value = call_user_func($options['modify'], $value);
            }
            if ($options['constraints'] !== null) {
                $problems = $this->validator->validateValue($value, $options['constraints']);
                if (count($problems) > 0) {
                    $messages = ["There were some errors in the provided value:", ""];

                    /** @var ConstraintViolation $problem */
                    foreach ($problems as $problem) {
                        $messages[] = "{$problem->getMessage()}";
                    }

                    /** @var FormatterHelper $formatter */
                    $formatter = $this->getHelper('formatter');
                    $this->getOutput()->writeln($formatter->formatBlock($messages, 'error', true));
                    continue;
                }
            }
            break;
        }
        return $value;
    }

    /**
     * Set the value of a property in arguments to the value interactively retrieved if it wasn't set previously.
     * Will also forcefully request an update for the property if the option 'force' is set.
     * @param Arguments                 $arguments
     * @param string                    $property
     * @param string|InputTypeInterface $type
     * @param array                     $options
     */
    public function update(Arguments $arguments, $property, $type, array $options = [])
    {
        if (!isset($arguments[$property]) || (isset($options['force']) && $options['force'])) {
            $this->set($arguments, $property, $type, $options);
        }
    }

    /**
     * Set the value of a property in arguments to the value interactively retrieved.
     * @param Arguments                 $arguments
     * @param string                    $property
     * @param string|InputTypeInterface $type
     * @param array                     $options
     */
    public function set(Arguments $arguments, $property, $type, array $options = [])
    {
        $options = $this->validateAndUpdateOptions($options, $type);
        $constraints = [];
        if (is_array($this->constraints) && isset($this->constraints[$property])) {
            $constraints = $this->constraints[$property];
        }

        $options['constraints'] = $constraints;
        $options['property'] = $property;
        if ($options['default'] === null && $arguments->get($property, null) !== null) {
            $options['default'] = $arguments[$property];
        }

        $result = $this->ask($type, $options);

        $arguments[$property] = $result;
    }

    /**
     * Validate options does not contain non-existant options and update with defaults where not defined.
     * @param array                     $options
     * @param string|InputTypeInterface $type
     * @return array
     * @throws GeneratorException
     * @throws InputTypeNotFoundException
     */
    private function validateAndUpdateOptions(array $options, $type)
    {
        if (is_string($type)) {
            $type = $this->types->getType($type);
        }

        if (!($type instanceof InputTypeInterface)) {
            throw new InputTypeNotFoundException("Type should be an instance of InputTypeInterface or a string");
        }

        $defaults = $this->getDefaultOptions($type);
        foreach ($options as $key => $value) {
            if (!array_key_exists($key, $defaults)) {
                $allowed = implode(', ', array_keys($defaults));
                throw new GeneratorException(
                    "Found non-default option '{$key}', only allowed to use one of {$allowed}."
                );
            }
        }
        return array_merge($defaults, $options);
    }

    /**
     * Return the default options for the InputTypeInterface combined with the default options
     * required by the questioner.
     * @param InputTypeInterface $type
     * @return array
     */
    private function getDefaultOptions(InputTypeInterface $type)
    {
        return array_merge([
            'force' => false,
            'property' => '',
            'constraints' => null,
            'required' => false,
            'default' => null,
            'modify' => null,
        ], $type->getDefaultOptions());
    }

    /**
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return HelperSet
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * @param string $name
     * @return HelperInterface
     */
    public function getHelper($name)
    {
        return $this->helperSet->get($name);
    }

    /**
     * Write a message in the given style, with line breaks at the given length.
     * @param string|array    $message      Message to be formatted.
     * @param int             $lineWidth    Maximum line width of the message.
     * @param string          $style        Style to be used for formatting the message.
     */
    public function message($message, $lineWidth = 80, $style = 'info')
    {
        if (null === $lineWidth) {
            $lineWidth = 80;
        }

        if (is_string($message)) {
            $message = explode("\n", wordwrap($message, $lineWidth, "\n", true));
        }

        foreach ($message as $line) {
            $line = str_pad(OutputFormatter::escape($line), $lineWidth);
            $this->getOutput()->writeln("<{$style}>{$line}</{$style}>");
        }
    }

    public function messageBlock($message, $lineWidth = 80, $style = 'info', $large = false)
    {
        if (null === $lineWidth) {
            $lineWidth = 80;
        }

        if (is_string($message)) {
            $message = explode("\n", wordwrap($message, $lineWidth, "\n", true));
        }

        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $this->writeln($formatter->formatBlock($message, $style, $large));
    }

    public function writeln($messages = "")
    {
        $this->getOutput()->writeln($messages);
    }

    public function write($messages)
    {
        $this->getOutput()->write($messages);
    }
}
