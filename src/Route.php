<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

use Closure;

/**
 * Class Route
 *
 * @package FastD\Routing
 */
class Route
{
    /**
     * @var array
     */
    public array $parameters = [];

    /**
     * @var string
     */
    public string $method = 'GET';

    /**
     * @var string|Closure
     */
    public $handler;

    /**
     * @var array
     */
    public array $middleware = [];

    /**
     * @var string
     */
    public string $regex;

    /**
     * @var array
     */
    public array $variables;


    /**
     * @param string $method
     * @param mixed $handler
     * @param string $regex
     * @param mixed[] $variables
     * @param array $middleware
     * @param array $parameters
     */
    public function __construct(string $method, $handler, string $regex, array $variables, array $middleware = [], array $parameters = [])
    {
        $this->method = $method;
        $this->handler = $handler;
        $this->regex = $regex;
        $this->variables = $variables;
        $this->middleware = $middleware;
        $this->parameters = $parameters;
    }

    /**
     * Tests whether this route matches the given string.
     * @param string $str
     * @return bool
     */
    public function matches(string $str): bool
    {
        $regex = '~^' . $this->regex . '$~';

        return (bool) preg_match($regex, $str);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return Route
     */
    public function setParameters(array $parameters): Route
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $middleware
     * @return $this
     */
    public function addMiddleware(string $middleware): Route
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * @param array $middlewares
     * @return Route
     */
    public function addMiddlewares(array $middlewares): Route
    {
        foreach ($middlewares as $middleware) {
            $this->middleware[] = $middleware;
        }

        return $this;
    }

}
