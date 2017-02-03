<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;
use SebastianBergmann\CodeCoverage\Report\PHP;

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
     * RouteRegex constructor.
     *
     * @param null $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;

        if ('*' === substr($path, -1)) {
            $this->regex = str_replace('/*', '(\/[_a-zA-Z0-9-]+){1,}', $this->path);
        }

        $this->parseRoute();
    }

    /**
     * @return bool
     */
    public function isStaticRoute()
    {
        if (empty($this->path)) {
            return true;
        }

        return false === strpos($this->path, '{');
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
     * @return mixed|string
     */
    protected function parseRoute()
    {
        if ($this->isStaticRoute()) {
            return $this->getPath();
        }

        $path = $this->getPath();

        preg_match_all('~' . self::VARIABLE_REGEX . '~x', $this->path, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $path = str_replace($match[0], '(' . (isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX) . ')', $path);

            $this->variables[] = $match[1];

            $this->requirements[$match[1]] = isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX;
        }

        $this->regex = str_replace(['[(', '+)]'], ['?(', '*)'], $path);

        if ('/' === substr($this->regex, -1)) {
            $this->regex .= '?';
        }

        unset($matches, $path);

        return $this->regex;
    }
}