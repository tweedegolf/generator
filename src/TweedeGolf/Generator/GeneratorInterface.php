<?php

namespace TweedeGolf\Generator;

use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Dispatcher\GeneratorDispatcherInterface;

interface GeneratorInterface
{
    public function getName();
    public function getDescription();
    public function getDefinition();

    /**
     * Generate the code as requested.
     * @param BuilderInterface $builder
     * @return mixed
     */
    public function generate(BuilderInterface $builder, GeneratorDispatcherInterface $dispatcher);

    /**
     * Indicate that an action was completed.
     * @param string       $what
     * @param null|string  $on
     * @param array        $arguments
     */
    public function did($what, $on = null, array $arguments = array());
}
