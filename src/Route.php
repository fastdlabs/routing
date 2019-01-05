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
class Route extends RouteRegex
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var mixed
     */
    protected $callback;

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * Route constructor.
     *
     * @param string $method
     * @param $path
     * @param $callback
     */
    public function __construct(string $method, string $path, $callback)
    {
        parent::__construct($path);

        $this->withMethod($method);

        $this->withCallback($callback);
    }

    /**
     * @param string $method
     * @return Route
     */
    public function withMethod(string $method): Route
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param mixed $callback
     * @return Route
     */
    public function withCallback($callback): Route
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return Closure
     */
    public function getCallback(): ?Closure
    {
        return $this->callback;
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
    public function withParameters(array $parameters): Route
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $parameters
     * @return Route
     */
    public function mergeParameters(array $parameters): Route
    {
        $this->parameters = array_merge($this->parameters, array_filter($parameters));

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function withMiddleware($middleware): Route
    {
        $this->middleware = [$middleware];

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function withAddMiddleware($middleware): Route
    {
        if (is_array($middleware)) {
            $this->middleware = array_merge($this->middleware, $middleware);
        } else {
            $this->middleware[] = $middleware;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
