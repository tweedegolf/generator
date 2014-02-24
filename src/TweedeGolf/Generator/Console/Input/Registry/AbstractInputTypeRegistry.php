<?php

namespace TweedeGolf\Generator\Console\Input\Registry;

use TweedeGolf\Generator\Console\Input\InputTypeInterface;
use TweedeGolf\Generator\Exception\InputTypeNotFoundException;

abstract class AbstractInputTypeRegistry implements InputTypeRegistryInterface
{
    /**
     * @var array
     */
    private $inputTypes;

    /**
     * @var array
     */
    private $priorities;

    public function __construct()
    {
        $this->inputTypes = [];
        $this->priorities = [];
        $this->addDefaultTypes();
    }

    /**
     * Add the default types to the InputTypeRegistry
     */
    abstract public function addDefaultTypes();

    /**
     * {@inheritdoc}
     */
    public function addType(InputTypeInterface $type, $name, $priority = 1)
    {
        if (!$this->hasType($name) || $this->priorities[$name] < $priority) {
            $this->inputTypes[$name] = $type;
            $this->priorities[$name] = $priority;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasType($name)
    {
        return isset($this->inputTypes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getType($name)
    {
        if (!$this->hasType($name)) {
            throw new InputTypeNotFoundException("Cannot create input prompt for type {$name}");
        }
        return $this->inputTypes[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableTypes()
    {
        return array_keys($this->inputTypes);
    }
}
