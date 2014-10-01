<?php

namespace Frmwrk;

/**
 * Class Controller
 * @package Frmwrk
 */
abstract class Controller
{
    /**
     * Init function called after constructor
     * @param array $variables
     */
    abstract function init($variables);

    /**
     * Called at the end of the process. It must return an array.
     * - The first parameter is the view name
     * - The second parameter is an array (key=>value) of vars to apply to the view
     * @return array
     */
    abstract function render();
} 