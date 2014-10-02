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

        //TODO:
        $this->generate();

        return $this->code;
    }

    /**
     * Create a cache file
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

namespace Frmwrk\Template;

class Regexp
{
    const ECHO_SYNTAXE_TEMPLATE = '/{{_([a-z0-9\[\]\'\"\->]+)_}}/';
    const ECHO_SYNTAXE_PHP      = '<?php echo $$1; ?>';

    const PRINT_SYNTAXE_TEMPLATE = '/_([a-z0-9]+)_/';
    const PRINT_SYNTAXE_PHP      = '$$1';

    const FOREACH_SYNTAXE_TEMPLATE = '/<foreach\s+var="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/';
    const FOREACH_SYNTAXE_PHP      = '<?php foreach($$1 as $$2): ?>';

    const FOREACH_WITH_KEY_SYNTAXE_TEMPLATE = '/<foreach\s+var="([a-z0-9_]+)"\s+key="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/';
    const FOREACH_WITH_KEY_SYNTAXE_PHP      = '<?php foreach($$1 as $$2 => $$3): ?>';

    const FOREACH_CLOSING_SYNTAXE_TEMPLATE = '/<\/foreach>/';
    const FOREACH_CLOSING_SYNTAXE_PHP      = '<?php endforeach; ?>';

    const IF_SYNTAXE_TEMPLATE = '/<if cond="([^"]+)">/';
    const IF_SYNTAXE_PHP      = '<?php if ($1): ?>';

    const ELSIF_SYNTAXE_TEMPLATE = '/<elsif cond="([^"]+)">/';
    const ELSIF_SYNTAXE_PHP      = '<?php elsif($1): ?>';

    const ELSE_SYNTAXE_TEMPLATE = '/<else>/';
    const ELSE_SYNTAXE_PHP      = '<?php else: ?>';

    const IF_CLOSING_SYNTAXE_TEMPLATE = '/<\/if>/';
    const IF_CLOSING_SYNTAXE_PHP      = '<?php endif; ?>';

    public static function getTemplateRegex()
    {
        return [
            self::ECHO_SYNTAXE_TEMPLATE,
            self::PRINT_SYNTAXE_TEMPLATE,
            self::FOREACH_SYNTAXE_TEMPLATE,
            self::FOREACH_WITH_KEY_SYNTAXE_TEMPLATE,
            self::FOREACH_CLOSING_SYNTAXE_TEMPLATE,
            self::IF_SYNTAXE_TEMPLATE,
            self::ELSIF_SYNTAXE_TEMPLATE,
            self::ELSE_SYNTAXE_TEMPLATE,
            self::IF_CLOSING_SYNTAXE_TEMPLATE
        ];
    }

    public static function getPHPRender()
    {
        return [
            self::ECHO_SYNTAXE_PHP,
            self::PRINT_SYNTAXE_PHP,
            self::FOREACH_SYNTAXE_PHP,
            self::FOREACH_WITH_KEY_SYNTAXE_PHP,
            self::FOREACH_CLOSING_SYNTAXE_PHP,
            self::IF_SYNTAXE_PHP,
            self::ELSIF_SYNTAXE_PHP,
            self::ELSE_SYNTAXE_PHP,
            self::IF_CLOSING_SYNTAXE_PHP
        ];
    }
}