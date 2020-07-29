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


use FastD\Routing\Traits\ResourcesTrait;

/**
 * Class RouteCollection
 *
 * @package FastD\Routing
 */
class RouteCollection
{
    use ResourcesTrait;

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
     * @param string $prefix
     * @param callable $callable
     * @param array $middleware
     * @return RouteCollection
     */
    public function group(string $prefix, callable $callable, $middleware = []): RouteCollection
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
     * @param string $method
     * @param string $path
     * @param $handler
     * @param array $middleware
     * @param array $parameters
     */
    public function addRoute(string $method, string $path, string $handler, array $middleware = [], array $parameters = [])
    {
        $path = $this->currentGroupPrefix . $path;
        $middleware = $this->currentGroupMiddleware + $middleware;
        $routeDatas = $this->routeParser->parse($path);
        foreach ((array) $method as $value) {
            foreach ($routeDatas as $routeData) {
                $this->routeMaps->addRoute($value, $routeData, $handler, (array) $middleware, (array) $parameters);
            }
        }
    }
}
