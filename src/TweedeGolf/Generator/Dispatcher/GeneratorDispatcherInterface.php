<?php

namespace TweedeGolf\Generator\Dispatcher;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Console\Input\Registry\InputTypeRegistryInterface;
use TweedeGolf\Generator\GeneratorInterface;
use TweedeGolf\Generator\Input\Arguments;
use TweedeGolf\Generator\Registry\GeneratorRegistryInterface;

interface GeneratorDispatcherInterface
{
    /**
     * Dispatch to the given generator a request to generate something using the given arguments.
     * @param string|GeneratorInterface $generator  The generator or name of generator that should generate something.
     * @param array|Arguments           $arguments  A list of arguments for the generator to consume.
     * @param string                    $path       Path to the target directory, or current working dir if not given.
     * @param bool                      $simulate   If true, nothing should be modified.
     */
    public function dispatch($generator, $arguments, $path = null, $simulate = false);

    /**
     * @return GeneratorRegistryInterface
     */
    public function getRegistry();

    /**
     * @return BuilderInterface
     */
    public function getBuilder();

    /**
     * @return InputTypeRegistryInterface
     */
    public function getInputTypeRegistry();

    /**
     * @return OutputInterface
     */
    public function getOutput();

    /**
     * @return HelperSet
     */
    public function getHelperSet();

    /**
     * @param string $helper
     * @return HelperInterface
     */
    public function getHelper($helper);

    /**
     * Bind the dispatcher to the current output and helperset.
     * @param OutputInterface   $output
     * @param HelperSet         $helperSet
     */
    public function bind(OutputInterface $output, HelperSet $helperSet);

    /**
     * Returns whether or not the dispatcher is bound to some output and helperset.
     * @return bool
     */
    public function bound();
}
