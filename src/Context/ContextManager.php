<?php

namespace Klever\ChainlinkModule\Context;

use Klever\ChainlinkModule\Context\Exception\InvalidArgumentException;
use Klever\ChainlinkModule\Context\Exception\RuntimeException;
use Symbid\Chainlink\Context;

class ContextManager
{
    protected $contextClass = 'Symbid\Chainlink\Context';

    protected $contexts = array();

    /**
     * @return string
     */
    public function getContextClass()
    {
        return $this->contextClass;
    }

    /**
     * @param string $contextClass
     */
    public function setContextClass($contextClass)
    {
        if (!class_exists($contextClass)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" can not be used as a Context class; class does not exist',
                $contextClass
            ));
        }

        $this->contextClass = $contextClass;
    }

    public function setContext($contextName, Context $context)
    {
        $this->contexts[$contextName] = $context;
    }

    public function getContext($contextName, $factory = true)
    {
        if (isset($this->contexts[$contextName])) {
            return $this->contexts[$contextName];
        }

        if ($factory === false) {
            return null;
        }

        $contextClass = $this->getContextClass();
        $context = new $contextClass;

        if (!$context instanceof Context) {
            throw new RuntimeException(sprintf(
                '"%s" must be an implementation of Symbid\Chainlink\Context',
                $contextClass
            ));
        }

        $this->setContext($contextName, $context);
        return $context;
    }

    public function hasContext($contextName)
    {
        return isset($this->contexts[$contextName]);
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }
}
