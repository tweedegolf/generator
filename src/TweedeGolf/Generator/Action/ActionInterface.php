<?php

namespace TweedeGolf\Generator\Action;

use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Builder\BuilderInterface;

interface ActionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * Execute the action with the given parameters.
     * @param array             $args     Arguments to the action.
     * @param BuilderInterface  $builder  The builder used for construction.
     * @param bool              $simulate If true, do not do anything, only simulate as if.
     */
    public function execute(ArgumentList $args, BuilderInterface $builder, $simulate);
}
