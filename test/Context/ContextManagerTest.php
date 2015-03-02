<?php

namespace KleverTest\ChainlinkModule\Context;

use Klever\ChainlinkModule\Context\ContextManager;
use Symbid\Chainlink\Context;

class ContextManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->manager = new ContextManager();
    }

    public function testContextClassAccessorMethods()
    {
        $this->assertEquals('Symbid\Chainlink\Context', $this->manager->getContextClass());
        $this->manager->setContextClass('KleverTest\ChainlinkModule\Context\TestAsset\ExtendedContext');
        $this->assertEquals('KleverTest\ChainlinkModule\Context\TestAsset\ExtendedContext', $this->manager->getContextClass());
    }

    public function testContextAccessorMethods()
    {
        $this->assertEquals(0, count($this->manager->getContexts()));
        $this->assertFalse($this->manager->hasContext('TEST'));
        $this->assertNull($this->manager->getContext('TEST', false));

        $context = new Context();
        $this->manager->setContext('TEST', $context);

        $this->assertEquals(1, count($this->manager->getContexts()));
        $this->assertTrue($this->manager->hasContext('TEST'));
        $this->assertSame($context, $this->manager->getContext('TEST', false));
    }

    public function testContextAccessorFactory()
    {
        $this->assertEquals(0, count($this->manager->getContexts()));
        $this->assertNull($this->manager->getContext('TEST', false));

        $this->assertInstanceOf($this->manager->getContextClass(), $this->manager->getContext('TEST'));
        $this->manager->setContextClass('KleverTest\ChainlinkModule\Context\TestAsset\ExtendedContext');
        $this->assertInstanceOf($this->manager->getContextClass(), $this->manager->getContext('TEST_EXTENDED'));
        $this->assertEquals(2, count($this->manager->getContexts()));
    }

    /**
     * @expectedException \Klever\ChainlinkModule\Context\Exception\InvalidArgumentException
     */
    public function testWillThrowExceptionOnNonExistentContextClass()
    {
        $this->manager->setContextClass('This\Does\Not\Exist');
    }

    /**
     * @expectedException \Klever\ChainlinkModule\Context\Exception\RuntimeException
     */
    public function testWillThrowExceptionOnInvalidContextClass()
    {
        $this->manager->setContextClass('stdClass');
        $this->manager->getContext('TEST');
    }
}
