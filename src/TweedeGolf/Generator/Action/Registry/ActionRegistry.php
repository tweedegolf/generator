<?php

namespace TweedeGolf\Generator\Action\Registry;

use TweedeGolf\Generator\Action\ActionInterface;
use TweedeGolf\Generator\Exception\ActionNotFoundException;

class ActionRegistry implements ActionRegistryInterface
{
    /**
     * @var array
     */
    private $actions;

    public function __construct()
    {
        $this->actions = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addAction(ActionInterface $action)
    {
        $this->actions[$action->getName()] = $action;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction($name)
    {
        if (isset($this->actions[$name])) {
            return $this->actions[$name];
        }
        throw new ActionNotFoundException("Action {$name} is not registered to the builder.");
    }

    /**
     * {@inheritdoc}
     */
    public function getActions()
    {
        return $this->actions;
    }
}
