<?php

namespace TweedeGolf\Generator\Dispatcher;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ValidatorInterface;
use TweedeGolf\Generator\Builder\BuilderInterface;
use TweedeGolf\Generator\Console\Input\Registry\InputTypeRegistryInterface;
use TweedeGolf\Generator\Console\Questioner;
use TweedeGolf\Generator\Exception\GeneratorException;
use TweedeGolf\Generator\Exception\OutputNotAvailableException;
use TweedeGolf\Generator\GeneratorInterface;
use TweedeGolf\Generator\Input\Arguments;
use TweedeGolf\Generator\Registry\GeneratorRegistryInterface;
use TweedeGolf\Generator\Util\ValidationProblemFormatter;

class GeneratorDispatcher implements GeneratorDispatcherInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var GeneratorRegistryInterface
     */
    private $registry;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var HelperSet
     */
    private $helperSet;

    /**
     * @var InputTypeRegistryInterface
     */
    private $inputTypes;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        BuilderInterface $builder,
        GeneratorRegistryInterface $registry,
        InputTypeRegistryInterface $inputTypes,
        ValidatorInterface $validator
    ) {
        $this->builder = $builder;
        $this->registry = $registry;
        $this->inputTypes = $inputTypes;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($generator, $arguments, $path = null, $simulate = false)
    {
        if (is_string($generator)) {
            $generator = $this->registry->getGenerator($generator);
        }

        if (is_array($arguments)) {
            $arguments = new Arguments($arguments);
        }

        $generator->before($arguments);
        $constraints = $generator->getConstraints();

        // retrieve interactive input
        if ($arguments->isForcedInteractive()) {
            $questioner = new Questioner(
                $this->getInputTypeRegistry(),
                $this->getOutput(),
                $this->getHelperSet(),
                $constraints,
                $this->validator
            );
            $generator->beforeInteract($arguments);
            $generator->interact($arguments, $questioner);
        }

        // run validation
        $generator->beforeValidate($arguments);
        $constraints = new Collection($constraints);
        $constraints->allowExtraFields = true;
        $problems = $this->validator->validateValue($arguments->getData(), $constraints);

        // stop if any problems are found
        if (count($problems) > 0) {
            $this->showValidationProblems($problems, $generator);
            throw new GeneratorException("Generator failed because of validation errors");
        }

        // create a builder for this specific generator
        $generator->beforeGenerate($arguments);
        $builder = $this->builder->forGenerator($generator, $arguments);
        if ($path !== null) {
            $builder = $builder->withPath($path);
        }
        $builder->simulated($simulate);

        // run the generator
        $generator->setOutput($this->output);
        $generator->generate($arguments, $builder, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeRegistry()
    {
        return $this->inputTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function bind(OutputInterface $output, HelperSet $helperSet)
    {
        $this->output = $output;
        $this->helperSet = $helperSet;
    }

    /**
     * {@inheritdoc}
     */
    public function bound()
    {
        return $this->output !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelper($helper)
    {
        return $this->getHelperSet()->get($helper);
    }

    /**
     * Displays output results of validation problems.
     * @param ConstraintViolationList $problems
     * @param GeneratorInterface      $generator
     * @throws OutputNotAvailableException
     */
    public function showValidationProblems(ConstraintViolationList $problems, GeneratorInterface $generator)
    {
        if (!$this->bound()) {
            throw new OutputNotAvailableException(
                "Tried to show validation errors, however no output channel available"
            );
        }

        $block = ValidationProblemFormatter::format($problems, $generator, $this->getHelper('formatter'));
        $this->getOutput()->writeln($block);
    }
}
