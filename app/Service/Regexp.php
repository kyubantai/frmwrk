<?php

namespace Frmwrk\Template;

/**
 * Class Regexp
 * @package Frmwrk\Template
 */
class Regexp
{
    /**
     * Echo
     */
    const ECHO_SYNTAXE_TEMPLATE = '/{{_([a-z0-9\[\]\'\"\->]+)_}}/';
    const ECHO_SYNTAXE_PHP      = '<?php echo $$1; ?>';

    /**
     * Var to php var
     */
    const PRINT_SYNTAXE_TEMPLATE = '/_([a-z0-9]+)_/';
    const PRINT_SYNTAXE_PHP      = '$$1';

    /**
     * Foreach (value only)
     */
    const FOREACH_SYNTAXE_TEMPLATE = '/<foreach\s+var="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/';
    const FOREACH_SYNTAXE_PHP      = '<?php foreach($$1 as $$2): ?>';

    /**
     * Foreach (key=>value)
     */
    const FOREACH_WITH_KEY_SYNTAXE_TEMPLATE = '/<foreach\s+var="([a-z0-9_]+)"\s+key="([a-z0-9_]+)"\s+as="([a-z0-9_]+)">/';
    const FOREACH_WITH_KEY_SYNTAXE_PHP      = '<?php foreach($$1 as $$2 => $$3): ?>';

    /**
     * Foreach (key=>value) REVERSE
     */
    const FOREACH_WITH_KEY_REVERSE_SYNTAXE_TEMPLATE = '/<foreach\s+var="([a-z0-9_]+)"\s+as="([a-z0-9_]+)"\s+key="([a-z0-9_]+)">/';
    const FOREACH_WITH_KEY_REVERSE_SYNTAXE_PHP      = '<?php foreach($$1 as $$3 => $$2): ?>';

    /**
     * Foreach eot
     */
    const FOREACH_CLOSING_SYNTAXE_TEMPLATE = '/<\/foreach>/';
    const FOREACH_CLOSING_SYNTAXE_PHP      = '<?php endforeach; ?>';

    /**
     * If
     */
    const IF_SYNTAXE_TEMPLATE = '/<if cond="([^"]+)">/';
    const IF_SYNTAXE_PHP      = '<?php if ($1): ?>';

    /**
     * Elsif
     */
    const ELSEIF_SYNTAXE_TEMPLATE = '/<elseif cond="([^"]+)">/';
    const ELSEIF_SYNTAXE_PHP      = '<?php elseif($1): ?>';

    /**
     * Else
     */
    const ELSE_SYNTAXE_TEMPLATE = '/<else>/';
    const ELSE_SYNTAXE_PHP      = '<?php else: ?>';

    /**
     * Endif
     */
    const IF_CLOSING_SYNTAXE_TEMPLATE = '/<\/if>/';
    const IF_CLOSING_SYNTAXE_PHP      = '<?php endif; ?>';

    /**
     * Get an array of all regex
     * @return array
     */
    public static function getTemplateRegex()
    {
        return [
            self::ECHO_SYNTAXE_TEMPLATE,
            self::PRINT_SYNTAXE_TEMPLATE,
            self::FOREACH_SYNTAXE_TEMPLATE,
            self::FOREACH_WITH_KEY_SYNTAXE_TEMPLATE,
            self::FOREACH_WITH_KEY_REVERSE_SYNTAXE_TEMPLATE,
            self::FOREACH_CLOSING_SYNTAXE_TEMPLATE,
            self::IF_SYNTAXE_TEMPLATE,
            self::ELSEIF_SYNTAXE_TEMPLATE,
            self::ELSE_SYNTAXE_TEMPLATE,
            self::IF_CLOSING_SYNTAXE_TEMPLATE
        ];
    }

    /**
     * Get an array of result strings
     * @return array
     */
    public static function getPHPRender()
    {
        return [
            self::ECHO_SYNTAXE_PHP,
            self::PRINT_SYNTAXE_PHP,
            self::FOREACH_SYNTAXE_PHP,
            self::FOREACH_WITH_KEY_SYNTAXE_PHP,
            self::FOREACH_WITH_KEY_REVERSE_SYNTAXE_PHP,
            self::FOREACH_CLOSING_SYNTAXE_PHP,
            self::IF_SYNTAXE_PHP,
            self::ELSEIF_SYNTAXE_PHP,
            self::ELSE_SYNTAXE_PHP,
            self::IF_CLOSING_SYNTAXE_PHP
        ];
    }
}