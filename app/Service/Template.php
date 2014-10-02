<?php

namespace Frmwrk;

/**
 * Class Template
 * @package Frmwrk
 */
class Template
{
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function fromCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $file
     * @throws \Exception
     */
    public function fromFile($file)
    {
        if (!is_file($file))
        {
            throw new \Exception('FilePathException');
        }

        $this->code = file_get_contents($file);
    }

    /**
     * Add a variable
     * @param string $key
     * @param mixed $value
     * @throws \Exception
     */
    public function add($key, $value)
    {
        if ($key == null || $value == null)
        {
            throw new \Exception('NullPointerException');
        }

        $this->vars[$key] = $value;
    }

    /**
     * Remove a variable
     * @param string $key
     * @throws \Exception
     */
    public function remove($key)
    {
        if (!isset($this->vars[$key]))
        {
            throw new \Exception('UndefinedKeyException');
        }

        $this->vars[$key] = null;
    }

    /**
     * Parse the loaded code and applies variables
     * @throws \Exception
     */
    public function parse()
    {
        if ($this->code == null)
        {
            throw new \Exception('MissingCodeException');
        }

        //TODO:
    }

    /**
     * Display the parsed code
     */
    public function display()
    {
        //TODO: cache?

        echo $this->code;
    }

} 