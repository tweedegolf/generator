<?php

namespace TweedeGolf\Generator\Builder;

use TweedeGolf\Generator\Action\ActionInterface;
use TweedeGolf\Generator\Exception\ActionNotFoundException;
use TweedeGolf\Generator\GeneratorInterface;

interface BuilderInterface
{
    /**
     * Retrieve a source resource file name.
     * @param string $resource
     * @return string
     */
    public function source($resource);

    /**
     * Retrieve a target file path.
     * @param string $file
     * @return string
     */
    public function target($file);

    /**
     * Indicate that an action was completed.
     * @param string       $what
     * @param null|string  $on
     * @param array        $arguments
     */
    public function did($what, $on = null, array $arguments = array());

    /**
     * Enter a subfolder and call the callback function.
     * The first parameter of the callback will be a new BuilderInterface with
     * that generates files relative to the specified subfolder.
     * @param string $subfolder
     * @param callback $callback
     */
    public function in($subfolder, $callback);

    /**
     * Run an action.
     * @param string|ActionInterface $action
     * @param array                  $arguments
     */
    public function exec($action, array $arguments);

    /**
     * Caller magic method for executing actions.
     */
    public function __call($method, array $arguments);

    /**
     * Retrieve a builder which is a clone of the current one except that it is bound to the given generator.
     * @param GeneratorInterface $generator
     * @return BuilderInterface
     */
    public function forGenerator(GeneratorInterface $generator);

    /**
     * Retrieve a builder which is a clone of the current one except that it has a different base target path.
     * @param string $path
     * @return BuilderInterface
     */
    public function withPath($path, $relative = true);

    /**
     * Set the builder to simulate all actions.
     * @param bool $simulated If false, the builder should no longer simulate its actions.
     */
    public function simulated($simulated = true);

    /**
     * Add an action to the builder.
     * @param ActionInterface $action
     */
    public function addAction(ActionInterface $action);

    /**
     * Retrieve an action by name.
     * @param string $action
     * @return ActionInterface
     * @throws ActionNotFoundException
     */
    public function getAction($action);

    /**
     * Returns whether or not the builder is simulating its actions.
     * @return bool
     */
    public function isSimulated();
}
