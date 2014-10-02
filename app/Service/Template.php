<?php

namespace Frmwrk;
use Frmwrk\Template\Regexp;

/**
 * Class Template
 * @package Frmwrk
 */
class Template
{
    /**
     * @var string
     */
    private $web_dir;

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
     * @param string $web_dir
     * @throws \Exception
     */
    public function __construct($web_dir)
    {
        if (!is_dir($web_dir))
        {
            throw new \Exception('WrongPathException');
        }

        $this->web_dir = $web_dir;
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
     * @return string
     */
    public function parse()
    {
        if ($this->code == null)
        {
            throw new \Exception('MissingCodeException');
        }

        // Parse all
        $this->code = preg_replace(Regexp::getTemplateRegex(), Regexp::getPHPRender(), $this->code);

        // Generate html code from parsed code
        $this->generate();

        return $this->code;
    }

    /**
     * Generate a html file from the parsed code
     */
    private function generate()
    {
        foreach($this->vars as $key => $value)
        {
            ${$key} = $value;
        }

        $hashname = hash('md5', $this->code);
        $tmp_path = $this->web_dir . '/tmp/' . $hashname;
        file_put_contents($tmp_path, $this->code);
        ob_start();
        require_once $tmp_path;
        $this->code = ob_get_contents();
        ob_end_clean();
        unlink($tmp_path);
    }
}