<?php

namespace KleverTest\ChainlinkModule\Factory;

use Klever\ChainlinkModule\Factory\ContextManager;
use Zend\ServiceManager\ServiceManager;

class ContextManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager $services
     */
    protected $services;

    public function setUp()
    {
        $this->services    = $services    = new ServiceManager();
        $this->factory     = $factory     = new ContextManager();
        $services->setService('Zend\ServiceManager\ServiceLocatorInterface', $services);
        $services->setService('Config', $this->getConfig());
        $services->setFactory('ChainlinkContextManager', $factory);

        //$services->setInvokableClass('EventManager', 'Zend\EventManager\EventManager');
        //$services->setInvokableClass('SharedEventManager', 'Zend\EventManager\SharedEventManager');
        //$services->setShared('EventManager', false);
    }
    public function getConfig()
    {
        return array(
            'klever-chainlink-module' => array(
                'context_manager' => array(
                    'contexts' => array(
                        'TEST' => array(
                            'handlers' => array(
                                'KleverTest\ChainlinkModule\Factory\TestAsset\Handler'
                            )
                        ),
                    ),
                ),
            ),
        );
    }

    public function testWillInstantiateHandlerIfServiceNotFoundButClassExists()
    {
        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $manager = $this->services->get('ChainlinkContextManager');
        $context = $manager->getContext('TEST');

        $property = new \ReflectionProperty($context, 'handlers');
        $property->setAccessible(true);
        $registeredHandlers = $property->getValue($context);

        $this->assertCount(1, $registeredHandlers);
        $this->assertInstanceOf('KleverTest\ChainlinkModule\Factory\TestAsset\Handler', $registeredHandlers[0]);
    }

    public function testWillReturnEmptyManagerOnMissingConfig()
    {
        $services = new ServiceManager();
        $services->setFactory('ChainlinkContextManager', $this->factory);

        $this->assertTrue($services->has('ChainlinkContextManager'));
        $manager = $services->get('ChainlinkContextManager');

        $this->assertCount(0, $manager->getContexts());
    }

    public function testWillReturnEmptyManagerOnEmptyConfig()
    {
        $config = $this->services->get('Config');
        $config['klever-chainlink-module'] = array();
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $manager = $this->services->get('ChainlinkContextManager');

        $this->assertCount(0, $manager->getContexts());
    }

    public function testWillPopulateContextClass()
    {
        $newContextClass = 'KleverTest\ChainlinkModule\Context\TestAsset\ExtendedContext';

        $config = $this->services->get('Config');
        $config['klever-chainlink-module']['context_manager']['context_class'] = $newContextClass;
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $manager = $this->services->get('ChainlinkContextManager');

        $this->assertEquals($newContextClass, $manager->getContextClass());
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testWillThrowExceptionOnMissingContextClass()
    {
        $newContextClass = 'This\Does\Not\Exist';

        $config = $this->services->get('Config');
        $config['klever-chainlink-module']['context_manager']['contexts']['TEST']['context_class'] = $newContextClass;
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $this->services->get('ChainlinkContextManager');
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testWillThrowExceptionOnInvalidContextClass()
    {
        $newContextClass = 'stdClass';

        $config = $this->services->get('Config');
        $config['klever-chainlink-module']['context_manager']['contexts']['TEST']['context_class'] = $newContextClass;
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $this->services->get('ChainlinkContextManager');
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testWillThrowExceptionOnInvalidHandler()
    {
        $handler = 'stdClass';

        $config = $this->services->get('Config');
        $config['klever-chainlink-module']['context_manager']['contexts']['TEST']['handlers'] = array($handler);
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $this->services->get('ChainlinkContextManager');
    }

    public function testWillGetHandlerFromServiceLocator()
    {
        $config = $this->services->get('Config');
        $config['klever-chainlink-module']['context_manager']['contexts']['TEST']['handlers'] = array('TestHandler');
        $this->services->setAllowOverride(true);
        $this->services->setService('Config', $config);
        $this->services->setInvokableClass('TestHandler', 'KleverTest\ChainlinkModule\Factory\TestAsset\Handler');

        $this->assertTrue($this->services->has('ChainlinkContextManager'));
        $manager = $this->services->get('ChainlinkContextManager');
        $context = $manager->getContext('TEST');

        $property = new \ReflectionProperty($context, 'handlers');
        $property->setAccessible(true);
        $registeredHandlers = $property->getValue($context);

        $this->assertCount(1, $registeredHandlers);
        $this->assertInstanceOf('KleverTest\ChainlinkModule\Factory\TestAsset\Handler', $registeredHandlers[0]);
    }
}
