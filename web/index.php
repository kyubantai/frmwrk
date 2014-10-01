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