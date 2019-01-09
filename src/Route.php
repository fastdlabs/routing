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
    protected $name = '';

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
     * @param $callback Support array, string, callable, function
     */
    public function __construct(string $method, string $path, $callback)
    {
        parent::__construct($path);

        $this->setMethod($method);

        $this->setCallback($callback);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function setName(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $method
     * @return Route
     */
    public function setMethod(string $method): Route
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
    public function setCallback($callback): Route
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
    public function setParameters(array $parameters): Route
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
    public function setMiddleware($middleware): Route
    {
        $this->middleware = [$middleware];

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function addMiddleware($middleware): Route
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
