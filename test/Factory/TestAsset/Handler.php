<?php

namespace KleverTest\ChainlinkModule\Factory\TestAsset;

use Symbid\Chainlink\Handler\HandlerInterface;

class Handler implements HandlerInterface
{
    /**
     * Is the handler capable of handling this input
     *
     * @param mixed $input
     * @return boolean
     */
    public function handles($input)
    {

    }

    /**
     * Execute actual handling of given input
     *
     * @param mixed $input
     * @return mixed
     */
    public function handle($input)
    {

    }
}
