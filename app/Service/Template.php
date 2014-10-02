<?php

namespace Frmwrk;

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

        $this->parseEcho();
        $this->parseForeach();
        $this->parseIf();

        $this->cache();

        return $this->code;
    }

    public function cache()
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

        file_put_contents($this->web_dir . '/cache/test.html', $this->code);
    }



    //////////////////
    // Parsing functions
    //////////////////
    /**
     * Parse echos
     */
    private function parseEcho()
    {
        $this->code = preg_replace('/{{_([a-z0-9\[\]\'\"\->]+)_}}/', '<?php echo $$1; ?>', $this->code);
        $this->code = preg_replace('/__([a-z0-9]+)__/',   '$$1',                $this->code);
    }

    private function parseForeach()
    {
        $this->code = preg_replace('/<foreach\s+var="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/',                      '<?php foreach($$1 as $$2): ?>',        $this->code);
        $this->code = preg_replace('/<foreach\s+var="([a-z0-9_]+)"\s+key="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/', '<?php foreach($$1 as $$2 => $$3): ?>', $this->code);
        $this->code = preg_replace('/<\/foreach>/',                                                             '<?php endforeach; ?>',                 $this->code);
    }

    private function parseIf()
    {
        $this->code = preg_replace('/<if cond="([^"]+)">/',    '<?php if ($1): ?>',   $this->code);
        $this->code = preg_replace('/<elsif cond="([^"]+)">/', '<?php elsif($1): ?>', $this->code);
        $this->code = preg_replace('/<else>/',                 '<?php else: ?>',      $this->code);
        $this->code = preg_replace('/<\/if>/',                 '<?php endif; ?>',     $this->code);
    }

} 