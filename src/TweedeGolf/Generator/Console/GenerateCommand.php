<?php

namespace TweedeGolf\Generator\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TweedeGolf\Generator\Dispatcher\GeneratorDispatcherInterface;
use TweedeGolf\Generator\GeneratorInterface;

class GenerateCommand extends Command
{
    /**
     * @var GeneratorDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var array
     */
    private $generatorDefinition;

    /**
     * Set the dispatcher for running generators.
     * @param GeneratorDispatcherInterface $dispatcher
     */
    public function setDispatcher(GeneratorDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Array of InputArgument and InputOption elements defining the standard generator arguments.
     * @return array
     */
    public function getGeneratorDefinition()
    {
        return $this->generatorDefinition;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setAliases(['tg:generate', 'tweedegolf:generate'])
            ->setDescription('Generate code for your project')
            ->ignoreValidationErrors()
        ;

        $this->generatorDefinition = [
            $this->getGeneratorArgument(true),
            new InputOption('simulate', 'm', InputOption::VALUE_NONE, 'If set, only simulate actions to be executed'),
            new InputOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'Base path for generating the results, current working directory by default'
            ),
            new InputOption('interactive', 'i', InputOption::VALUE_NONE, 'Force running the command interactively'),
        ];

        // by default the generator command uses a slightly different command
        $defaultDefinition = $this->generatorDefinition;
        $defaultDefinition[0] = $this->getGeneratorArgument(false);
        $defaultDefinition[] = new InputArgument(
            'variables',
            InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
            'Variables for the generator'
        );
        $this->setDefinition($defaultDefinition);
    }

    private function getGeneratorArgument($required = true)
    {
        return new InputArgument(
            'generator',
            $required ? InputArgument::REQUIRED : InputArgument::OPTIONAL,
            'The generator to use'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generatorName = $input->getArgument('generator');
        $vars = $input->getArgument('variables');
        if ('help' === $generatorName) {
            if (count($vars) > 0) {
                $generator = $this->dispatcher->getRegistry()->getGenerator($vars[0]);
                $this->showGeneratorHelp($generator, $output);
            } else {
                $this->showGeneratorList($output);
            }
        } elseif (null !== $generatorName) {
            $simulate = $input->getOption('simulate');
            $path = $input->getOption('path');
            $interactive = $input->getOption('interactive');

            // retrieve generator
            $generator = $this->dispatcher->getRegistry()->getGenerator($generatorName);

            // get arguments from command line
            $resolver = new CommandInputResolver($this->getApplication(), $this->getGeneratorDefinition());
            $arguments = $resolver->resolve($input, $generator, $interactive);

            // if interactivity is required but not available, stop right here
            if ($arguments->isForcedInteractive() && !$input->isInteractive()) {
                throw new \RuntimeException("Cannot run command interactively");
            }

            // bind the output and helperset and dispatch the command
            $this->dispatcher->bind($output, $this->getHelperSet());
            $this->dispatcher->dispatch($generator, $arguments, $path, $simulate);
        } else {
            $this->showGeneratorList($output);
        }
    }

    /**
     * Show the help for a single generator.
     * @param GeneratorInterface $generator
     * @param OutputInterface    $output
     */
    protected function showGeneratorHelp(GeneratorInterface $generator, OutputInterface $output)
    {
        $definition = new InputDefinition($generator->getDefinition());

        $output->writeln("<comment>Generator:</comment> <info>{$generator->getName()}</info>");
        $output->writeln(" {$generator->getDescription()}");
        $output->writeln("");

        $output->writeln("<comment>Usage:</comment>");
        $output->writeln(" {$this->getName()} {$generator->getName()} {$definition->getSynopsis()}");
        $output->writeln("");

        $descriptor = new DescriptorHelper();
        $descriptor->describe($output, $definition);
    }

    /**
     * Show the list of generators available.
     * @param OutputInterface $output
     */
    protected function showGeneratorList(OutputInterface $output)
    {
        $output->writeln("<comment>Available generators:</comment>");
        $helpCommand = "<info>{$this->getName()} help [generator]</info>";
        $output->writeln("Use {$helpCommand} for more information on each generator.");

        $rows = [];

        /** @var GeneratorInterface $generator */
        foreach ($this->dispatcher->getRegistry()->getGenerators() as $generator) {
            $rows[] = array("<info>{$generator->getName()}</info>", $generator->getDescription());
        }

        /** @var TableHelper $table */
        $table = $this->getHelper('table');
        $table->setLayout(TableHelper::LAYOUT_BORDERLESS);
        $table->setHorizontalBorderChar('');
        $table->setRows($rows);
        $table->render($output);
    }
}
