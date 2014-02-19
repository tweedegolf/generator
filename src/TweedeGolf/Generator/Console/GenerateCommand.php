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

    public function setDispatcher(GeneratorDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setAliases(['tg:generate', 'tweedegolf:generate'])
            ->setDescription('Generate things')
            ->addArgument('generator', InputArgument::OPTIONAL, 'The generator to use')
            ->addArgument('variables', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Variables for the generator')
            ->addOption('simulate', 's', InputOption::VALUE_NONE, 'If set, only simulate actions to be executed')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Base path for generating the results, current working directory by default')
            ->ignoreValidationErrors()
        ;
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

            $this->dispatcher->dispatch($generatorName, $input, $path, $simulate);
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

//        if ($generator->hasLongDescription()) {
//            $output->writeln("");
//            foreach ($generator->getLongDescription() as $line) {
//                $output->writeln($line);
//            }
//        }
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
