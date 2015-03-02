Chainlink ZF2 Module
=======

Introduction
------------

This module provides structure and code wrapping around Symbid\Chainlink exposing it to Zend Framework 2.

Requirements
------------
  
Please see the [composer.json](composer.json) file.

Installation
------------

Run the following `composer` command:

```console
$ composer require "klever/chainlink-zf2-module:dev-master"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "klever/chainlink-zf2-module": "dev-master"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'Klever\ChainlinkModule',
    ),
    /* ... */
);
```

#### User configuration example:

```php
array(
    'context_manager' => array(
        'contexts' => array(
            'MyContext' => array(
                'handlers' => array(
                    'MyHandler1',
                    'MyHandler2',
                    'MyHandler3',
                )
            ),
        ),
    ),
)
```
