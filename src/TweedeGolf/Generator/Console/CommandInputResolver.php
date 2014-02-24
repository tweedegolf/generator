<?php

namespace TweedeGolf\Generator\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use TweedeGolf\Generator\GeneratorInterface;
use TweedeGolf\Generator\Input\Arguments;

class CommandInputResolver
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var array
     */
    private $generatorDefinition;

    public function __construct(Application $application = null, array $generatorDefinition = [])
    {
        $this->application = $application;
        $this->generatorDefinition = $generatorDefinition;
    }

    /**
     * Grab the input arguments for the given generator from the input, or ask interactively.
     * @param InputInterface        $input              Input from which the arguments should be resolved.
     * @param GeneratorInterface    $generator          The generator for which input arguments should be grabbed.
     * @param bool                  $forceInteractive   Try to run the interactive versions of asking for input.
     * @throws \RuntimeException
     * @return Arguments
     */
    public function resolve(InputInterface $input, GeneratorInterface $generator, $forceInteractive = false)
    {
        $definition = $generator->getDefinition();
        $definition = $this->mergeApplicationDefinition($definition);
        $definition = new InputDefinition($definition);

        $input = clone $input;
        try {
            $input->bind($definition);
            $input->validate();
        } catch (\Exception $ex) {
            $forceInteractive = true;
        }
        $arguments = array_merge($input->getArguments(), $input->getOptions());
        $arguments = new Arguments($arguments, $forceInteractive || $generator->requiresInteraction());
        return $arguments;

        if ($forceInteractive || $generator->requiresInteraction()) {
            if (!$input->isInteractive()) {
                throw new \RuntimeException("Cannot interact with user.");
            }
            $constraints = $generator->getConstraints();
            $questioner = new Questioner($inputTypeRegistry, $this->output, $this->helperSet, $constraints);
            $arguments = $generator->interact($arguments, $questioner);
        }
        return $arguments;
    }

    /**
     * @param array $definition
     * @return array
     */
    private function mergeApplicationDefinition(array $definition)
    {
        array_splice($definition, 0, 0, $this->generatorDefinition);

        if ($this->application) {
            $appDefinition = $this->application->getDefinition();
            array_splice($definition, 0, 0, $appDefinition->getArguments());
            array_splice($definition, 0, 0, $appDefinition->getOptions());
        }

        return $definition;
    }
}
