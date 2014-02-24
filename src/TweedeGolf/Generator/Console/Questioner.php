<?php

namespace TweedeGolf\Generator\Console;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use TweedeGolf\Generator\Console\Input\InputTypeInterface;
use TweedeGolf\Generator\Console\Input\Registry\InputTypeRegistryInterface;
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

    public function __construct(
        InputTypeRegistryInterface $types,
        OutputInterface $output,
        HelperSet $helperSet,
        array $constraints
    ) {
        $this->types = $types;
        $this->output = $output;
        $this->helperSet = $helperSet;
        $this->constraints = $constraints;
    }

    /**
     * @param string|InputTypeInterface $type
     * @param array                     $options
     * @param Constraint[]|Constraint   $constraints
     * @return mixed
     * @throws InputTypeNotFoundException
     */
    public function ask($type, array $options = [], $constraints = [])
    {
        if (is_string($type)) {
            $type = $this->types->getType($type);
        }

        if (!($type instanceof InputTypeInterface)) {
            throw new InputTypeNotFoundException("Type should be an instance of InputTypeInterface or a string");
        }

        return $type->ask($options, $this->output, $this->helperSet);
    }

    /**
     * @param Arguments                 $arguments
     * @param string                    $property
     * @param string|InputTypeInterface $type
     * @param array                     $options
     */
    public function update(Arguments $arguments, $property, $type, array $options = [])
    {

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
}
