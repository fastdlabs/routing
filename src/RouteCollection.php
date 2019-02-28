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

/**
 * Class RouteCollection
 *
 * @package FastD\Routing
 */
class RouteCollection
{
    const ROUTES_CHUNK = 10;

    /**
     * @var array
     */
    protected $prefix = [];

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @var ?Route
     */
    protected $activeRoute;

    /**
     * @var int
     */
    protected $num = 1;

    /**
     * 路由分组计数器
     *
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $regexes = [];

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * @var Route[]
     */
    public $staticRoutes = [];

    /**
     * @var Route[]
     */
    public $dynamicRoutes = [];

    /**
     * @var array
     */
    public $aliasMap = [];

    /**
     * RouteCollection constructor.
     * @param string $namespace
     */
    public function __construct(string $namespace = '')
    {
        $this->namespace = $namespace;
    }

    /**
     * @param string $path
     * @return RouteCollection
     */
    public function prefix(string $path): RouteCollection
    {
        array_push($this->prefix, $path);

        return $this;
    }

    /**
     * @param mixed $middleware
     * @return RouteCollection
     */
    public function middleware($middleware): RouteCollection
    {
        array_push($this->middleware, $middleware);

        return $this;
    }

    /**
     * @param mixed ...$args
     * @return RouteCollection
     */
    public function group(...$args): RouteCollection
    {
        $callable = array_pop($args);

        count($args) == 2 && $this->prefix($args[0]);

        $callable($this);

        $this->restorePrefix();
        $this->restoreMiddleware();

        return $this;
    }

    /**
     * @param $callback
     * @return string
     */
    protected function concat($callback): string
    {
        return !is_string($callback) ? $callback : $this->namespace.$callback;
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
     * @param $callback
     * @return Route
     */
    public function addRoute(string $method, string $path, $callback): Route
    {
        $path = implode('/', $this->prefix).$path;

        $route = new Route($method, $path, $callback);

        $method = $route->getMethod();

        if (isset($this->aliasMap[$method][$path])) {
            return $this->aliasMap[$method][$path];
        }

        $route->addMiddleware($this->middleware);

        if ($route->isStatic()) {
            $this->staticRoutes[$method][$path] = $route;
        } else {
            $numVariables = count($route->getVariables());
            $numGroups = max($this->num, $numVariables);
            $this->regexes[$method][] = $route->getRegex().str_repeat('()', $numGroups - $numVariables);

            $this->dynamicRoutes[$method][$this->index]['regex'] = '~^(?|'.implode('|', $this->regexes[$method]).')$~';
            $this->dynamicRoutes[$method][$this->index]['routes'][$numGroups + 1] = $route;

            ++$this->num;

            if (count($this->regexes[$method]) >= static::ROUTES_CHUNK) {
                ++$this->index;
                $this->num = 1;
                $this->regexes[$method] = [];
            }
            unset($numGroups, $numVariables);
        }

        $this->aliasMap[$method][$path] = $route;

        return $route;
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

    /**
     * @return RouteCollection
     */
    protected function restoreMiddleware(): RouteCollection
    {
        array_pop($this->middleware);

        return $this;
    }

    /**
     * @return RouteCollection
     */
    protected function restorePrefix(): RouteCollection
    {
        array_pop($this->prefix);

        return $this;
    }
}