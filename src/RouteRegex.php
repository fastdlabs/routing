<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/fastdlabs
 * @link      https://www.fastdlabs.com/
 */

namespace FastD\Routing;


/**
 * Class RouteRegex
 *
 * @package FastD\Routing
 */
class RouteRegex
{
    private const VARIABLE_REGEX = <<<'REGEX'
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
    protected array $variables = [];

    /**
     * @var array
     */
    protected array $requirements = [];

    /**
     * @var string
     */
    protected string $regex;

    /**
     * @var string
     */
    protected string $path = '';

    /**
     * @var bool
     */
    protected bool $isStatic = true;

    /**
     * RouteRegex constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->parseRoute($path);
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @return array
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function parseRoute(string $path): string
    {
        if ('/' !== $path) {
            $path = rtrim($path, '/');
        }

        $this->path = $path;

        if ('*' !== substr($path, -1) && false === strpos($path, '{')) {
            return $this->path;
        }

        if ('*' === substr($this->path, -1)) {
            $this->isStatic = false;
            $requirement = '([\/_a-zA-Z0-9-]+){1,}';
            $this->regex = str_replace('/*', $requirement, $this->path);
            $this->variables = [
                'path'
            ];
            $this->requirements = [
                'path' => $requirement,
            ];
            unset($requirement);
            return $this->regex;
        }

        $this->isStatic = false;

        if (preg_match_all('~' . self::VARIABLE_REGEX . '~x', $path, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $path = str_replace($match[0], '(' . (isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX) . ')', $path);
                $this->variables[] = $match[1];
                $this->requirements[$match[1]] = isset($match[2]) ? $match[2] : static::DEFAULT_DISPATCH_REGEX;
            }
        }

        $this->regex = str_replace(['[(', '+)]'], ['?(', '*)'], $path) . '/?';

        unset($matches, $path);

        return $this->regex;
    }
}
