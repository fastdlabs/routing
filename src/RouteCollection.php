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
     * @var string
     */
    protected $ascribe;

    /**
     * @var Route
     */
    protected $activeRoute;

    /**
     * @var array
     */
    public $staticRoutes = [];

    /**
     * @var array
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
     * @var RouteCache
     */
    public $cache;

    /**
     * RouteCollection constructor.
     */
    public function __construct()
    {
        $this->cache = new RouteCache($this);
    }

    /**
     * @param $name
     * @return $this
     */
    public function ascribe($name)
    {
        $this->ascribe = $name;

        return $this;
    }

    /**
     * @param          $path
     * @param callable $callback
     */
    public function group($path, callable $callback)
    {
        array_push($this->with, $path);

        $callback($this);

        array_pop($this->with);
    }

    /**
     * @param $name
     * @return bool|Route
     */
    public function getRoute($name)
    {
        foreach ($this->aliasMap as $method => $routes) {
            if (isset($routes[$name])) {
                return $routes[$name];
            }
        }

        return false;
    }

    /**
     * @param $method
     * @param $path
     * @param $callback
     * @param null $name
     * @param array $defaults
     * @return Route
     */
    protected function createRoute($method, $path, $callback, $name = null, $defaults = [])
    {
        return new Route($method, $path, $callback, $name, $defaults);
    }

    /**
     * @param $method
     * @param $path
     * @param $callback
     * @param null $name
     * @param array $defaults
     * @return $this
     */
    public function addRoute($method, $path, $callback, $name = null, array $defaults = [])
    {
        $name = empty($name) ? $path : $name;

        if (isset($this->aliasMap[$method][$name])) {
            return $this;
        }

        $path = implode('/', $this->with) . $path;

        $route = $this->createRoute($method, $path, $callback, $name, $defaults);

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

        $this->aliasMap[$method][$name] = $route;

        return $this;
    }

    /**
     * @param $method
     * @param $path
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match($method, $path)
    {
        if (!isset($this->staticRoutes[$method][$path])) {
            if (!isset($this->staticRoutes['ANY'][$path])) {
                $dynamicRoutes = $this->dynamicRoutes;
                $routes = isset($dynamicRoutes[$method]) ? $dynamicRoutes[$method] : [];
                unset($dynamicRoutes[$method]);

                $match = function ($path, $data) {
                    if (!preg_match($data['regex'], $path, $matches)) {
                        return false;
                    }
                    $route = $data['routes'][count($matches)];

                    if (!($route instanceof Route)) {
                        $route = $this->createRoute($route['method'], $route['path'], $route['callback'], $route['name'], $route['defaults']);
                    }

                    preg_match('~^' . $route->getRegex() . '$~', $path, $match);

                    $match = array_slice($match, 1, count($route->getVariables()));
                    $route->mergeParameters(array_combine($route->getVariables(), $match));

                    return $route;
                };

                foreach ($routes as $data) {
                    if (false !== ($route = $match($path, $data))) {
                        return $route;
                    }
                }

                foreach ($dynamicRoutes as $dynamicRoute) {
                    foreach ($dynamicRoute as $data) {
                        if (false !== ($route = $match($path, $data))) {
                            $route->setMethod($method);
                            return $route;
                        }
                    }
                }

                throw new RouteNotFoundException($path);
            }
            $route = $this->staticRoutes['ANY'][$path];
        } else {
            $route = $this->staticRoutes[$method][$path];
        }

        if (!($route instanceof Route)) {
            $route = $this->createRoute($route['method'], $route['path'], $route['callback'], $route['name'], $route['defaults']);
        }

        return $route;
    }

    /**
     * @param string $method
     * @param string $path
     * @return mixed
     */
    public function dispatch($method, $path)
    {
        $route = $this->match($method, $path);

        return call_user_func_array($route->getCallback(), $route->getParameters());
    }

    /**
     * @param $name
     * @param array $parameters
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function generateUrl($name, array $parameters = [], $format = '')
    {
        if (false === ($route = $this->getRoute($name))) {
            throw new RouteNotFoundException($name);
        }

        if (!($route instanceof Route)) {
            $route = $this->createRoute($route['method'], $route['path'], $route['callback'], $route['name'], $route['defaults']);
        }

        if (!empty($format)) {
            $format = '.' . $format;
        } else {
            $format = '';
        }

        if ($route->isStaticRoute()) {
            return $route->getPath() . $format;
        }

        $parameters = array_merge($route->getParameters(), $parameters);
        $queryString = [];

        foreach ($parameters as $key => $parameter) {
            if (!in_array($key, $route->getVariables())) {
                $queryString[$key] = $parameter;
                unset($parameters[$key]);
            }
        }

        $search = array_map(function ($v) {
            return '{' . $v . '}';
        }, array_keys($parameters));

        $replace = $parameters;

        $path = str_replace($search, $replace, $route->getPath());

        if (false !== strpos($path, '[')) {
            $path = str_replace(['[', ']'], '', $path);
            $path = rtrim(preg_replace('~(({.*?}))~', '', $path), '/');
        }

        return $path . $format . ([] === $queryString ? '' : '?' . http_build_query($queryString));
    }

    /**
     * @param $dir
     */
    public function loadCaching($dir)
    {
        $this->cache->loadCache($dir);
    }

    /**
     * @param $dir
     */
    public function caching($dir)
    {
        $this->cache->saveCache($dir);
    }
}