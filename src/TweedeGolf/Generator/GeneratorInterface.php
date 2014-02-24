<?php

namespace TweedeGolf\Generator;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Console\Questioner;
use TweedeGolf\Generator\Dispatcher\GeneratorDispatcherInterface;
use TweedeGolf\Generator\Input\Arguments;

interface GeneratorInterface
{
    /**
     * Retrieve the name of the generator.
     * @return string
     */
    public function getName();

    /**
     * Retrieve the description of the generator.
     * @return string
     */
    public function getDescription();

    /**
     * Retrieve the console component definition.
     * @return array
     */
    public function getDefinition();

    /**
     * Generate the code as requested.
     * @param Arguments                    $arguments
     * @param BuilderInterface             $builder
     * @param GeneratorDispatcherInterface $dispatcher
     * @return
     */
    public function generate(Arguments $arguments, BuilderInterface $builder, GeneratorDispatcherInterface $dispatcher);

    /**
     * Indicate that an action was completed.
     * @param string       $what
     * @param null|string  $on
     * @param array        $arguments
     */
    public function did($what, $on = null, array $arguments = array());

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output);

    /**
     * Interact with the user to retrieve the rest of the arguments.
     * @param Arguments     $arguments  Arguments already known.
     * @param Questioner    $questioner
     */
    public function interact(Arguments $arguments, Questioner $questioner);

    /**
     * Return the constraints for the arguments of the generator.
     * @return Constraint[]
     */
    public function getConstraints();

    /**
     * Returns true if the generator always requires interaction to do its job.
     * @return bool
     */
    public function requiresInteraction();

    /**
     * Function ran after arguments are known and before any processing is done.
     * @param Arguments $arguments
     */
    public function before(Arguments $arguments);

    /**
     * Function ran before interaction will take place, but only if it takes place.
     * @param Arguments $arguments
     */
    public function beforeInteract(Arguments $arguments);

    /**
     * Function ran before validation takes place, but after the input was received (either interactively or not).
     * @param Arguments $arguments
     */
    public function beforeValidate(Arguments $arguments);

    /**
     * Function ran before the generator is actually executed, but after validation.
     * @param Arguments $arguments
     */
    public function beforeGenerate(Arguments $arguments);
}
