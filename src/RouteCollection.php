<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


/**
 * Class RouteCollection
 *
 * @package FastD\Routing
 */
class RouteCollection
{
    /**
     * @var string
     */
    protected string $currentGroupPrefix = '';

    /**
     * @var array
     */
    protected array $currentGroupMiddleware = [];

    /**
     * @var RouteParser
     */
    public RouteParser $routeParser;

    /**
     * @var RouteMaps
     */
    public RouteMaps $routeMaps;

    /**
     * RouteCollection constructor.
     */
    public function __construct()
    {
        $this->routeParser = new RouteParser;
        $this->routeMaps = new RouteMaps();
    }

    /**
     * @param string $method
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function addRoute(string $method, string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $path = $this->currentGroupPrefix . $path;
        $middleware = $this->currentGroupMiddleware + $middleware;
        $routeDatas = $this->routeParser->parse($path);
        foreach ((array) $method as $value) {
            foreach ($routeDatas as $routeData) {
                $this->routeMaps->addRoute($value, $routeData, $handler, $middleware, $parameters);
            }
        }
    }

    /**
     * @param string $prefix
     * @param callable $callable
     * @param array $middleware
     * @return RouteCollection
     */
    public function group(string $prefix, callable $callable, array $middleware = []): RouteCollection
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $previousGroupMiddleware = $this->currentGroupMiddleware;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware + $middleware;
        $callable($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware;

        return $this;
    }

    /**
     * Adds a GET route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function get(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a POST route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function post(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a PUT route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function put(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a PATCH route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function patch(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('PATCH', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a DELETE route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function delete(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $parameters);
    }

    /**
     * Adds a OPTIONS route to the collection
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     * @return mixed
     */
    public function options(string $path, string $handler, array $middleware = [], array $parameters = []): void
    {
        $this->addRoute('OPTIONS', $path, $handler, $middleware, $parameters);
    }
}
