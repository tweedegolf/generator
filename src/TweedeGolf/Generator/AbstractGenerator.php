<?php

namespace TweedeGolf\Generator;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TweedeGolf\Generator\Exception\InvalidInputException;
use TweedeGolf\Generator\Input\Arguments;

abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var array
     */
    private $definition;

    /**
     * @var bool
     */
    private $interactionRequired;

    public function __construct()
    {
        $this->definition = [];
        $this->configure();
    }

    /**
     * Configure the generator.
     */
    abstract public function configure();

    /**
     * Set the short description of the generator.
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the name of the generator.
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set this generator to require interaction.
     * @param bool $interact
     * @return $this
     */
    public function mustInteract($interact = true)
    {
        $this->interactionRequired = $interact;
        return $this;
    }

    /**
     * Adds an argument.
     *
     * @param string  $name        The argument name
     * @param integer $mode        The argument mode: one of the InputArgument::* constants
     * @param string  $description A description text
     * @param mixed   $default     The default value
     * @return $this The current instance
     */
    public function addArgument($name, $mode = null, $description = '', $default = null)
    {
        $this->definition[] = new InputArgument($name, $mode, $description, $default);
        return $this;
    }

    /**
     * Adds an option.
     *
     * @param string  $name        The option name
     * @param string  $shortcut    The shortcut (can be null)
     * @param integer $mode        The option mode: One of the InputOption::VALUE_* constants
     * @param string  $description A description text
    * @param mixed   $default     The default value
    * @return $this The current instance
    */
    public function addOption($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        $this->definition[] = new InputOption($name, $shortcut, $mode, $description, $default);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * {@inheritdoc}
     */
    public function did($what, $on = null, array $arguments = array(), $indent = 0)
    {
        if (!($this->output instanceof OutputInterface)) {
            return;
        }

        if ($on === null) {
            $on = "";
        } else {
            $on = " " . $on;
        }

        $indent = str_repeat(" ", $indent * 2);
        $args = implode(', ', $arguments);

        if (strlen($args) > 0) {
            $args = " <fg=cyan>{$args}</fg=cyan>";
        }
        $this->output->writeln("{$indent}<info>{$what}</info>{$args}{$on}");
    }

    /**
     * {@inheritdoc}
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresInteraction()
    {
        return $this->interactionRequired;
    }

    /**
     * {@inheritdoc}
     */
    public function before(Arguments $arguments)
    {
        // Default empty method, override if needed
    }

    /**
     * {@inheritdoc}
     */
    public function beforeInteract(Arguments $arguments)
    {
        // Default empty method, override if needed
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate(Arguments $arguments)
    {
        // Default empty method, override if needed
    }

    /**
     * {@inheritdoc}
     */
    public function beforeGenerate(Arguments $arguments)
    {
        // Default empty method, override if needed
    }
}
