<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;


use FastD\Routing\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
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
     * @var array
     */
    protected array $prefix = [];

    /**
     * @var array
     */
    protected array $middleware = [];

    /**
     * @var Route
     */
    protected Route $activeRoute;

    /**
     * 路由分组计数器
     *
     * @var int
     */
    protected int $index = 0;

    /**
     * @var string
     */
    protected string $currentGroupPrefix = '';

    /**
     * @var array
     */
    protected array $currentGroupMiddleware = [];

    /**
     * @var Route[]
     */
    public array $staticRoutes = [];

    /**
     * @var Route[]
     */
    public array $dynamicRoutes = [];

    /**
     * @var array
     */
    public array $aliasMap = [];

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
     * @param string $path
     * @return RouteCollection
     */
    public function prefix(string $path): RouteCollection
    {
        $this->currentGroupPrefix = $path;

        return $this;
    }

    /**
     * @param string $middleware
     * @return RouteCollection
     */
    public function middleware(string $middleware): RouteCollection
    {
        array_push($this->middleware, $middleware);

        return $this;
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
     * @return Route
     */
    public function getActiveRoute(): Route
    {
        return $this->activeRoute;
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

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match(ServerRequestInterface $serverRequest): Route
    {
        $method = $serverRequest->getMethod();
        $path = $serverRequest->getUri()->getPath();

        if (isset($this->staticRoutes[$method][$path])) {
            return $this->activeRoute = $this->staticRoutes[$method][$path];
        } else {
            $possiblePath = $path;
            if ('/' === substr($possiblePath, -1)) {
                $possiblePath = rtrim($possiblePath, '/');
            } else {
                $possiblePath .= '/';
            }
            if (isset($this->staticRoutes[$method][$possiblePath])) {
                return $this->activeRoute = $this->staticRoutes[$method][$possiblePath];
            }
            unset($possiblePath);
        }

        if (
            ! isset($this->dynamicRoutes[$method])
            || false === $route = $this->matchDynamicRoute($serverRequest, $method, $path)
        ) {
            throw new RouteNotFoundException($method, $path);
        }

        return $this->activeRoute = $route;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @param $method
     * @param $path
     * @return Route
     */
    protected function matchDynamicRoute(ServerRequestInterface $serverRequest, $method, $path): Route
    {
        foreach ($this->dynamicRoutes[$method] as $data) {
            if (!preg_match($data['regex'], $path, $matches)) {
                continue;
            }
            $route = $data['routes'][count($matches)];
            preg_match('~^'.$route->getRegex().'$~', $path, $match);
            $match = array_slice($match, 1, count($route->getVariables()));
            $attributes = array_combine($route->getVariables(), $match);
            $attributes = array_filter($attributes);
            $route->mergeParameters($attributes);
            foreach ($route->getParameters() as $key => $attribute) {
                $serverRequest->withAttribute($key, $attribute);
            }

            return $route;
        }

        throw new RouteNotFoundException($method, $path);
    }

    protected function restoreMiddleware(): void
    {
        array_pop($this->middleware);
    }

    protected function restorePrefix(): void
    {
        array_pop($this->prefix);
    }
}
