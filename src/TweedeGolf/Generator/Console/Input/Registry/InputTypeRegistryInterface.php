<?php

namespace TweedeGolf\Generator\Console\Input\Registry;


use TweedeGolf\Generator\Console\Input\InputTypeInterface;
use TweedeGolf\Generator\Exception\InputTypeNotFoundException;

interface InputTypeRegistryInterface
{
    /**
     * @param InputTypeInterface $type      Type to add to the registry.
     * @param string             $name      Name of the type.
     * @param int                $priority  Priority for the type over other types with the same name.
     */
    public function addType(InputTypeInterface $type, $name, $priority = 1);

    /**
     * Returns whether or not an input type with the given name is registered.
     * @param string $name
     * @return bool
     */
    public function hasType($name);

    /**
     * @param string $name
     * @return InputTypeInterface
     * @throws InputTypeNotFoundException
     */
    public function getType($name);

    /**
     * Return a list of available type names.
     * @return array
     */
    public function getAvailableTypes();
}
