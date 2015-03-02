<?php

namespace KleverTest\ChainlinkModule;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

$loader = include __DIR__ . '/../vendor/autoload.php';
$loader->setPsr4('KleverTest\\ChainlinkModule\\', __DIR__);
