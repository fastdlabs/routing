<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Routing\Handle\RouteHandleInterface;

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
    protected array $parameters = [];

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $method = 'GET';

    /**
     * @var string
     */
    protected string $handle;

    /**
     * @var array
     */
    protected array $middleware = [];

    /**
     * Route constructor.
     *
     * @param string $method
     * @param $path
     */
    public function __construct(string $method, string $path)
    {
        parent::__construct($path);

        $this->setMethod($method);
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
     * @param $handle $handle
     * @return Route
     */
    public function handle(string $handle): Route
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return RouteHandleInterface
     */
    public function getHandle(): RouteHandleInterface
    {
        return new $this->handle;
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
     * @param array $middleware
     * @return Route
     */
    public function setMiddleware(array $middleware): Route
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function addMiddleware(string $middleware): Route
    {
        $this->middleware[] = $middleware;

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
