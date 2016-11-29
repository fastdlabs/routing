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
     * @var string|array
     */
    protected $method = 'ANY';

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $middleware = [];

    /**
     * Route constructor.
     *
     * @param string $method
     * @param $path
     * @param $callback
     * @param array $defaults
     */
    public function __construct($method = 'ANY', $path, $callback, array $defaults = [])
    {
        parent::__construct($path);

        $this->withMethod($method);

        $this->withCallback($callback);

        $this->withParameters($defaults);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array|string $method
     * @return $this
     */
    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $callback
     * @return $this
     */
    public function withCallback($callback = null)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function withParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function mergeParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, array_filter($parameters));

        return $this;
    }

    /**
     * @param $middleware
     * @return $this
     */
    public function middleware($middleware)
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    /**
     * use laravel using.
     *
     * @param $name
     * @return Route
     */
    public function name($name)
    {
        return $this->withName($name);
    }
}
