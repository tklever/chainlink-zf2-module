<?php

return array(
    'klever-chainlink-module' => array(
//        'context_manager' => array(
//            'context_class' => 'Name of Symbid\Chainlink\Context derivative, if not using that class',
//            'contexts' => array(
//                'Name of Context' => array(
//                    'context_class' => 'Name of Symbid\Chainlink\Context derivative, if not using that class',
//                    'handlers' => array(
//                        'Name of service/class that acts as a handler',
//                        // repeat for each handler you want to define
//                    )
//                ),
//                // repeat for each context you want to define
//            ),
//        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'ChainlinkContextManager' => 'Klever\ChainlinkModule\Factory\ContextManager',
        ),
    ),
);
