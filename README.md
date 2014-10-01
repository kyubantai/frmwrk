Frmwrk
======

Frmwrk is a simple and young framework. It's currently only composed of a View-Controller system (As soon as possible, model part will be included), but you can use your own database manager.

Usage
=====

Just include the autoload.php file (generated by composer), init the config and instantiate it!

```php
<?php

require __DIR__ . '/../vendor/autoload.php';
$config =
[
'controllers' => __DIR__ . '/../app/Controllers/',
'views'       => __DIR__ . '/views/'
];
\Frmwrk\Engine::init($config);
$instance = \Frmwrk\Engine::getInstance();
$instance->render();
```

Configuration
=============

Configuration array has several parameters :

* controllers: It must contain the path to the controllers folder
* views: It must contain the path to the views folder
* default_controller: (default: 'index') It's the name of the default controller
* notfound: (default: 'notfound') It's the name of the controller which is loaded when the asked controller does not exists.
* pretty_url: (default: true) If enabled, you will use a clearer way to pass variables through url. (See next part for more details)

Pretty URL
==========

The pretty url feature is only used to simplify urls simply. Here are some syntaxes:

```
http://domain.tld/CONTROLLER_NAME
http://domain.tld/CONTROLLER_NAME/VAR_NAME_1/VAR_VALUE_1
http://domain.tld/CONTROLLER_NAME/VAR_NAME_1/VAR_VALUE_1/VAR_NAME_2/VAR_VALUE_2/
ect...
```

The first parameter, called CONTROLLER_NAME, is the name of the controller you want to load.

The next parameters always works by pair. The first one will always be the name of the variable and the second one will be the value.

```
http://domain.tld/index/lang/fr/
will load the controller 'index' and send him the variable lang which contains 'fr'.
```

These variables are sent in the init() function of the current Controller.

```php
<?php
class index extends \Frmwrk\Controller
{
    public function init($variables)
    {
        print_r($variables['_GET']);  // Will display $_GET variables
        print_r($variables['_POST']); // Will display $_POST variables
        print_r($variables['_URL']);  // Will display Pretty URL variables

        // ...
    }
}
```

Question?
=========

If you have any questions or if you think there are missing information in this readme.md, please notice me.