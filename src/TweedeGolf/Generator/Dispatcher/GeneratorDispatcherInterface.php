<?php

namespace TweedeGolf\Generator\Dispatcher;

use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\GeneratorInterface;
use TweedeGolf\Generator\Registry\GeneratorRegistryInterface;

interface GeneratorDispatcherInterface
{
    /**
     * @param string|GeneratorInterface $generator
     * @param array                     $arguments
     * @param string                    $path
     * @param bool                      $simulate
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
}
