<?php

namespace Klever\ChainlinkModule\Factory;

use Symbid\Chainlink\Context;
use Symbid\Chainlink\Handler\HandlerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Klever\ChainlinkModule\Context\ContextManager as Manager;

class ContextManager implements FactoryInterface
{
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config')) {
            return array();
        }

        $config = $serviceLocator->get('Config');

        if (!isset($config['klever-chainlink-module']['context_manager'])) {
            return array();
        }

        $config = $config['klever-chainlink-module']['context_manager'];
        return $config;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $manager = new Manager();

        $this->setManagerOptions($config, $manager);

        if (isset($config['contexts']) && is_array($config['contexts'])) {
            $this->injectContexts($manager, $config['contexts'], $serviceLocator);
        }

        return $manager;
    }

    protected function setManagerOptions($config, Manager $manager)
    {
        foreach ($config as $option => $value) {
            switch ($option) {
                case 'context_class':
                    $manager->setContextClass($value);
                    break;
            }
        }
    }

    protected function injectContexts(Manager $manager, $config, ServiceLocatorInterface $serviceLocator)
    {
        foreach ($config as $contextName => $contextConfig) {
            $contextClass = isset($contextConfig['context_class'])
                ? $contextConfig['context_class'] : $manager->getContextClass();

            if (!class_exists($contextClass)) {
                throw new ServiceNotCreatedException(sprintf(
                    '"%s" is an invalid context_class; class does not exist',
                    $contextClass
                ));
            }

            $context      = new $contextClass;

            if (!$context instanceof Context) {
                throw new ServiceNotCreatedException(sprintf(
                    '"%s" must be an implementation of Symbid\Chainlink\Context',
                    $contextClass
                ));
            }

            if (isset($contextConfig['handlers']) && is_array($contextConfig['handlers'])) {
                $this->injectHandlers($context, $contextConfig['handlers'], $serviceLocator);
            }

            $manager->setContext($contextName, $context);
        }
    }

    protected function injectHandlers(Context $context, $config, ServiceLocatorInterface $serviceLocator)
    {
        foreach ($config as $handlerName) {
            if ($serviceLocator->has($handlerName)) {
                $handler = $serviceLocator->get($handlerName);
            } else {
                $handler = new $handlerName;
            }

            if (!$handler instanceof HandlerInterface) {
                throw new ServiceNotCreatedException(sprintf(
                    '%s expects that the handler implements '
                    . 'Symbid\Chainlink\Handler\HandlerInterface; received %s',
                    __METHOD__,
                    (is_object($handler) ? get_class($handler) : gettype($handler))
                ));
            }

            $context->addHandler($handler);
        }
    }
}
