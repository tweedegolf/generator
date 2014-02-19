<?php

namespace TweedeGolf\Generator\Action\Registry;

use TweedeGolf\Generator\Action\ActionInterface;
use TweedeGolf\Generator\Exception\ActionNotFoundException;

interface ActionRegistryInterface
{
    /**
     * @param ActionInterface $action
     */
    public function addAction(ActionInterface $action);

    /**
     * @param string $name
     * @return ActionInterface
     * @throws ActionNotFoundException
     */
    public function getAction($name);

    /**
     * @return array
     */
    public function getActions();
}
