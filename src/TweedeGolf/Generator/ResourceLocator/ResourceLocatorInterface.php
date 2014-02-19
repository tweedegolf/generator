<?php

namespace TweedeGolf\Generator\ResourceLocator;

use TweedeGolf\Generator\GeneratorInterface;

interface ResourceLocatorInterface
{
    /**
     * Locate a resource and return a path to it.
     * @param string                $resource   The name of the resource.
     * @param GeneratorInterface    $generator  The generator requesting the resource.
     * @return string
     */
    public function locate($resource, GeneratorInterface $generator);
}
