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
    protected $with = [];

    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @var Route
     */
    protected $activeRoute;

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
     * 路由列表计数器
     *
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
     * @param          $prefix
     * @param callable $callback
     * @param $middleware
     * @return $this
     */
    public function group($prefix, callable $callback, $middleware = null)
    {
        !empty($middleware) && $this->middleware = $middleware;

        array_push($this->with, $prefix);

        $callback($this);

        array_pop($this->with);

        $this->middleware = [];

        return $this;
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function get($path, $callback, array $defaults = [])
    {
        return $this->addRoute('GET', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function post($path, $callback, array $defaults = [])
    {
        return $this->addRoute('POST', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function put($path, $callback, array $defaults = [])
    {
        return $this->addRoute('PUT', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function delete($path, $callback, array $defaults = [])
    {
        return $this->addRoute('DELETE', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function head($path, $callback, array $defaults = [])
    {
        return $this->addRoute('HEAD', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function patch($path, $callback, array $defaults = [])
    {
        return $this->addRoute('PATCH', $path, $callback, $defaults);
    }

    /**
     * @param $path
     * @param $handle
     * @return Route[]
     */
    public function resource($path, $handle)
    {
        $routes = [];
        foreach (['GET', 'POST', 'PATCH', 'DELETE'] as $method) {
            $routes[] = $this->addRoute($method, $path, $handle);
        }
        return $routes;
    }

    /**
     * @return Route
     */
    public function getActiveRoute()
    {
        return $this->activeRoute;
    }

    /**
     * @param $method
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function createRoute($method, $path, $callback, $defaults = [])
    {
        return new Route($method, $path, $callback, $defaults);
    }

    /**
     * @param $method
     * @param $path
     * @param $callback
     * @param array $defaults
     * @return Route
     */
    public function addRoute($method, $path, $callback, array $defaults = [])
    {
        $path = implode('/', $this->with) . $path;

        $route = $this->createRoute($method, $path, $callback, $defaults);
        $route->withAddMiddleware($this->middleware);

        if ($route->isStaticRoute()) {
            $this->staticRoutes[$method][$path] = $route;
        } else {
            $numVariables = count($route->getVariables());
            $numGroups = max($this->num, $numVariables);
            $this->regexes[$method][] = $route->getRegex() . str_repeat('()', $numGroups - $numVariables);

            $this->dynamicRoutes[$method][$this->index]['regex'] = '~^(?|' . implode('|', $this->regexes[$method]) . ')$~';
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
    public function match(ServerRequestInterface $serverRequest)
    {
        $method = $serverRequest->getMethod();
        $path = $serverRequest->getUri()->getPath();

        if (isset($this->staticRoutes[$method][$path])) {
            return $this->activeRoute = $this->staticRoutes[$method][$path];
        }

        if (
            !isset($this->dynamicRoutes[$method])
            || false === ($route = $this->matchDynamicRoute($serverRequest, $method, $path))
        ) {
            throw new RouteNotFoundException($path);
        }

        return $this->activeRoute = $route;
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @param string $method
     * @param string $path
     * @return bool|Route
     */
    protected function matchDynamicRoute(ServerRequestInterface $serverRequest, $method, $path)
    {
        foreach ($this->dynamicRoutes[$method] as $data) {
            if (!preg_match($data['regex'], $path, $matches)) {
                continue;
            }
            $route = $data['routes'][count($matches)];
            preg_match('~^' . $route->getRegex() . '$~', $path, $match);
            $match = array_slice($match, 1, count($route->getVariables()));
            $attributes = array_combine($route->getVariables(), $match);
            $attributes = array_filter($attributes);
            $route->mergeParameters($attributes);
            foreach ($route->getParameters() as $key => $attribute) {
                $serverRequest->withAttribute($key, $attribute);
            }
            return $route;
        }

        return false;
    }
}