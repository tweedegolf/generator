<?php

namespace TweedeGolf\Generator\ResourceLocator;

use TweedeGolf\Generator\GeneratorInterface;

class DirectoryResourceLocator implements ResourceLocatorInterface
{
    /**
     * @var string
     */
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * {@inheritdoc}
     */
    public function locate($resource, GeneratorInterface $generator)
    {
        $location = $this->location;
        if ($location[-1] !== '/' && $location[-1] !== '\\') {
            $location .= '/';
        }

        if (strpos($location, '%generator%') !== false) {
            $directory = str_replace('%generator%', $generator->getName(), $location);
        } else {
            $directory = $location . $generator->getName() . '/';
        }
        return $directory . $resource;
    }
}
