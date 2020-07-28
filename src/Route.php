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
     * @param string $path
     * @param $handler
     */
    public function __construct(string $method, string $path, $handler)
    {
        parent::__construct($path);

        $this->method = $method;
        $this->handle = $handler;
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
     * @param string $handle
     * @return $this
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
        $handle = new $this->handle;

        if (!($handle instanceof RouteHandleInterface)) {
            throw new \RuntimeException(sprintf('Route handle must be implement %s', RouteHandleInterface::class));
        }

        return $handle;
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
    public function before(string $middleware): Route
    {
        //$this->middleware['after'][] = $middleware;

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return Route
     */
    public function after(string $middleware): Route
    {
        //$this->middleware['before'][] = $middleware;

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

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}
