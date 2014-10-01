<?php

namespace Frmwrk;

require_once __DIR__ . '/Controller.php';

/**
 * Class Engine
 * @package Frmwrk
 */
class Engine
{
    /////////////////////
    // Static
    /////////////////////

    /**
     * @var Engine Static instance of Engine class
     */
    private static $_instance;

    /**
     * @var array Default config (must be )
     */
    private static $_config = [
        'views'              => '/views',        // Views path
        'controllers'        => '/controllers',  // Controllers path
        'default_controller' => 'index', // Default controller name
        'pretty_url'         => true     // Use Frmwrk's url rewriting (Needs a htaccess/nginx redirection)
    ];

    /**
     * Set the config and check the validity of its parameters
     * @param array $config
     * @throws \Exception
     */
    public static function init($config)
    {
        self::$_config = array_merge(self::$_config, $config);

        if (!is_dir(self::$_config['views']))
        {
            throw new \Exception('InvalidPathException: ' . self::$_config['views']);
        }

        if (!is_dir(self::$_config['controllers']))
        {
            throw new \Exception('InvalidPathException: ' . self::$_config['controllers']);
        }

        if (!self::checkController(self::$_config['default_controller']))
        {
            throw new \Exception('InvalidControllerException: ' . self::$_config['default_controller']);
        }
    }

    /**
     * Check if a controller file exists
     * @param string $controllerName
     * @return bool
     */
    public static function checkController($controllerName)
    {
        // Check if file exists
        if (!is_file(self::getControllerPath($controllerName)))
        {
            return false;
        }

        // Check if class exists
        if (!array_key_exists($controllerName, self::file_get_php_classes(self::getControllerPath($controllerName))))
        {
            return false;
        }

        return true;
    }

    /**
     * Return view's file path
     * @param string $viewName
     * @return string
     */
    public static function getViewPath($viewName)
    {
        return self::$_config['views'] . '/' . $viewName . '.php';
    }

    /**
     * Check if specified view exists
     * @param string $viewName
     * @return bool
     */
    public static function checkView($viewName)
    {
        if (!is_file(self::getViewPath($viewName)))
        {
            return false;
        }

        return true;
    }

    /**
     * Get the instance of the Engine.
     * (If not existing, it will create a new one)
     * @return Engine The class instance
     */
    public static function getInstance()
    {
        if (self::$_instance == null)
        {
            self::$_instance = new Engine();
        }

        return self::$_instance;
    }

    /**
     * Returns controller's file path
     * @param string $controllerName
     * @return string
     */
    public static function getControllerPath($controllerName)
    {
        return self::$_config['controllers'] . '/' . $controllerName . '.php';
    }


    /**
     * Get array of class names defined in a file
     * From: http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
     * @param string $filepath
     * @return array
     */
    private static function file_get_php_classes($filepath) {
        $php_code = file_get_contents($filepath);
        $classes = self::get_php_classes($php_code);
        return $classes;
    }

    /**
     * Get array of class names defined in a code
     * From: http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
     * @param string $php_code
     * @return array
     */
    private static function get_php_classes($php_code) {
        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if (   $tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;
    }




    /////////////////////
    // Member
    /////////////////////

    /**
     * Array of parsed variables
     * @var array
     */
    private $variables = [
        '_GET'  => [],
        '_POST' => []
    ];

    /**
     * Current controller
     * @var string
     */
    private $controller = null;

    private function __construct()
    {
        $this->setController(self::$_config['default_controller']);
        $this->handleVariables();
    }

    /**
     * @param string $controllerName
     */
    private function setController($controllerName)
    {
        $this->controller = $controllerName;
    }

    /**
     * Handle GET, POST and PRETTY_URL variables
     * @throws \Exception
     */
    private function handleVariables()
    {
        if (isset($_GET) && !empty($_GET))
        {
            $this->variables['_GET'] = $_GET;

            if (!self::$_config['pretty_url'] && isset($this->variables['_GET']['v']))
            {
                $this->setController($this->variables['_GET']['v']);
            }
        }

        if (isset($_POST) && !empty($_POST))
        {
            $this->variables['_POST'] = $_POST;
        }

        if (self::$_config['pretty_url'])
        {
            $url = $_SERVER['REQUEST_URI'];
            $self = $_SERVER['PHP_SELF'];
            $pos = strrpos($self, "/");
            $url = substr($url, $pos);

            $array = explode('/', $url);
            $array = array_filter($array, "strlen");
            $array = array_values($array);

            if (isset($array[0]) && $this->checkController($array[0]))
            {
                $this->setController($array[0]);
            }

            $i = 2;
            $this->variables['_URL'] = [];
            while (isset($array[$i]))
            {
                $this->variables['_URL'][$array[$i - 1]] = $array[$i];
                $i += 2;
            }
        }

        if (!self::checkController($this->controller))
        {
            throw new \Exception('InvalidControllerName: ' . $this->controller);
        }
    }

    /**
     * Applies variables and load specified view
     * @param $parameters
     * @throws \Exception
     */
    private function loadView($parameters)
    {
        if (!isset($parameters[0]) || self::checkView($parameters[0]))
        {
            throw new \Exception('InvalidViewName: ' . $parameters[0]);
        }

        if (isset($parameters[1]) && is_array($parameters[1]))
        foreach($parameters[1] as $key => $value)
        {
            ${$key} = $value;
        }

        require_once self::getViewPath($parameters[0]);
    }

    /**
     * Load and execute the controller
     * @throws \Exception
     */
    public function render()
    {
        if (!self::checkController($this->controller))
        {
            throw new \Exception('InvalidControllerName: ' . $this->controller);
        }

        require $this->getControllerPath($this->controller);

        if (!class_exists($this->controller))
        {
            throw new \Exception('ClassNotDefinedException: ' . $this->controller);
        }

        $controller_instance = new $this->controller();

        if (!is_subclass_of($controller_instance, 'Frmwrk\Controller'))
        {
            throw new \Exception('ClassIsNotAController: ' . $this->controller);
        }

        $controller_instance->init($this->variables);

        $render = $controller_instance->render();
        $this->loadView($render);
    }
} 