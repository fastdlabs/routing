<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


/**
 * Class RouteRegex
 *
 * @package FastD\Routing
 */
class RouteRegex
{
    const VARIABLE_REGEX = <<<'REGEX'
\{
    ([a-zA-Z0-9_?*]*)
    (?:
        :([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    const DEFAULT_DISPATCH_REGEX = '[^/]+';
    const DEFAULT_OPTIONAL_REGEX = '[^/]*';

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var array
     */
    protected $requirements = [];

    /**
     * @var string
     */
    protected $regex;

    /**
     * @var null
     */
    protected $path;

    /**
     * @var bool
     */
    protected $isStatic = true;

    /**
     * RouteRegex constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->parseRoute($path);
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @return null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function parseRoute($path)
    {
        if ('/' !== $path) {
            $path = rtrim($path, '/');
        }

        $this->path = $path;

        if ('*' !== substr($path, -1) && false === strpos($path, '{')) {
            return $this->path;
        }

        $this->isStatic = false;

        if (preg_match_all('~' . self::VARIABLE_REGEX . '~x', $path, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $path = str_replace($match[0], '(' . (isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX) . ')', $path);
                $this->variables[] = $match[1];
                $this->requirements[$match[1]] = isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX;
            }
        } else {
            $this->variables[] = 'path';
            $this->requirements['path'] = '([\/_a-zA-Z0-9-]*)';
        }

        $this->regex = str_replace(['*', '[(', '+)]'], ['([\/_a-zA-Z0-9-]*)', '?(', '*)'], $path) . '/?';

        unset($matches, $path);

        return $this->regex;
    }
}