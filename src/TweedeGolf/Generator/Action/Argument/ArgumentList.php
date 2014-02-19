<?php

namespace TweedeGolf\Generator\Action\Argument;

use TweedeGolf\Generator\Exception\NotEnoughArgumentsException;

class ArgumentList implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new NotEnoughArgumentsException("No argument found at position {$offset}");
        }
        return $this->data[$offset];
    }

    /**
     * Retrieve a parameter or return the default.
     * @param mixed $offset
     * @param mixed $default
     */
    public function get($offset, $default = null)
    {
        if (!$this->offsetExists($offset)) {
            return $default;
        } else {
            return $this->offsetGet($offset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException("Cannot change arguments");
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException("Cannot change arguments");
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }
}
