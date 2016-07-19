<?php
/**
 *
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
    \s* ([a-zA-Z][a-zA-Z0-9_]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    const DEFAULT_DISPATCH_REGEX = '[^/]+';

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
     * RouteRegex constructor.
     *
     * @param null $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;

        $this->parseRoute($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isStaticRoute($path)
    {
        if (empty($path)) {
            return true;
        }

        return false === strpos($path, '{');
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
    public function getVariable()
    {
        return $this->variables;
    }

    /**
     * @param $path
     * @return mixed|string
     */
    public function parseRoute($path)
    {
        if ($this->isStaticRoute($path)) {
            return $path;
        }

        preg_match_all('~' . self::VARIABLE_REGEX . '~x', $path, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $path = str_replace($match[0], '(' . ($match[2] ?? static::DEFAULT_DISPATCH_REGEX) . ')', $path);

            $this->variables[] = $match[1];

            $this->requirements[$match[1]] = $match[2] ?? static::DEFAULT_DISPATCH_REGEX;
        }

        $this->regex = str_replace(['[(', ')]'], ['?(', ')'], $path);

        return $this->regex;
    }
}