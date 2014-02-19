<?php

namespace TweedeGolf\Generator\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use TweedeGolf\Generator\Dispatcher\GeneratorDispatcherInterface;

class GenerateApplication extends Application
{
    /**
     * @var GeneratorDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param GeneratorDispatcherInterface $dispatcher
     */
    public function __construct(GeneratorDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'generate';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $generateCommand = new GenerateCommand();
        $generateCommand->setDispatcher($this->dispatcher);

        $defaultCommands[] = $generateCommand;
        return $defaultCommands;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();
        return $inputDefinition;
    }
}
