<?php

class notfound extends \Frmwrk\Controller
{
    /**
     * Init function called after constructor
     * @param array $variables
     */
    function init($variables)
    {

    }

    /**
     * Called at the end of the process. It must return an array.
     * - The first parameter is the view name
     * - The second parameter is an array (key=>value) of vars to apply to the view
     * @return array
     */
    function render()
    {
        http_response_code(404);

        return [ '404' ];
    }

} 