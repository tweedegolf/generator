<?php

namespace TweedeGolf\Generator\Builder;

use TweedeGolf\Generator\Action\ActionInterface;
use TweedeGolf\Generator\Action\Argument\ArgumentList;
use TweedeGolf\Generator\Action\Registry\ActionRegistry;
use TweedeGolf\Generator\Action\Registry\ActionRegistryInterface;
use TweedeGolf\Generator\GeneratorInterface;
use TweedeGolf\Generator\ResourceLocator\ResourceLocatorInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    /**
     * Locator for locating resources such as standard files and templates.
     * @var ResourceLocatorInterface
     */
    private $locator;

    /**
     * Generated bound to this builder.
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * Available actions registry.
     * @var ActionRegistryInterface
     */
    private $actions;

    /**
     * Path for target files.
     * @var string
     */
    private $path;

    /**
     * Number of parent builders from this builder.
     * @var int
     */
    private $depth;

    /**
     * Whether or not to simulate all actions to be executed.
     * @var bool
     */
    private $simulate;

    public function __construct(ResourceLocatorInterface $locator, $path = null, $simulate = false)
    {
        $this->locator = $locator;
        $this->depth = 0;
        if ($path === null) {
            $path = getcwd();
        }
        $this->path = $path;

        $this->simulated($simulate);
        $this->actions = new ActionRegistry();
        foreach ($this->getDefaultActions() as $action) {
            $this->addAction($action);
        }
    }

    /**
     * Return an array of actions available for the builder.
     * @return ActionInterface[]
     */
    abstract public function getDefaultActions();

    /**
     * {@inheritdoc}
     */
    public function forGenerator(GeneratorInterface $generator)
    {
        $builder = clone $this;
        $builder->generator = $generator;
        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path, $relative = true)
    {
        $builder = clone $this;
        if ($relative) {
            $path = $this->target($path);
        }
        $builder->path = $path;
        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function simulated($simulate = true)
    {
        $this->simulate = $simulate;
    }

    /**
     * {@inheritdoc}
     */
    public function isSimulated()
    {
        return $this->simulate;
    }

    /**
     * {@inheritdoc}
     */
    public function addAction(ActionInterface $action)
    {
        $this->actions->addAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function getAction($action)
    {
        return $this->actions->getAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function source($resource)
    {
        if (null === $this->generator) {
            throw new \BadMethodCallException("Must first bind generator before locating resources.");
        }
        return $this->locator->locate($resource, $this->generator);
    }

    /**
     * {@inheritdoc}
     */
    public function target($file)
    {
        // return string itself if file is (a) absolute path linux (b, c) absolute path windows (d) full url
        if ($file[0] === '/' ||
            $file[0] === '\\' ||
            (strlen($file) > 3 && ctype_alpha($file[0]) && $file[1] === ':' && ($file[2] === '/' ||
                $file[2] === '\\')) ||
            null !== parse_url($file, PHP_URL_SCHEME)
        ) {
            return $file;
        } elseif ($this->path[-1] === '/' || $this->path[-1] === '\\') {
            return $this->path . $file;
        } else {
            return $this->path . '/' . $file;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function did($what, $on = null, array $arguments = array())
    {
        if (null === $this->generator) {
            throw new \BadMethodCallException("Must first bind generator before running actions");
        }
        $this->generator->did($what, $on, $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function in($subfolder, $callback)
    {
        $target = $this->target($subfolder);

        /** @var AbstractBuilder $builder */
        $builder = $this->withPath($target, false);
        $builder->depth += 1;
        $this->did('enter', $subfolder);
        $callback($builder);
        $this->did('leave');
    }

    /**
     * {@inheritdoc}
     */
    public function exec($action, array $arguments)
    {
        if (is_string($action)) {
            $action = $this->getAction($action);
        }

        if (!($action instanceof ActionInterface)) {
            throw new \BadMethodCallException("Not received a valid action.");
        }

        $args = new ArgumentList($arguments);
        $action->execute($args, $this, $this->simulate);
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, array $arguments)
    {
        $this->exec($method, $arguments);
    }
}
