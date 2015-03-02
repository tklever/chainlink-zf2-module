<?php

namespace KleverTest\ChainlinkModule;

use Klever\ChainlinkModule\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigReturnsArray()
    {
        $module = new Module();
        $config = $module->getConfig();
        $this->assertInternalType('array', $config);
    }
}
