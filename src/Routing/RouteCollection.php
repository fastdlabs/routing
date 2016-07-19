<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/18
 * Time: 下午10:28
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

/**
 * Class RouteCollection
 *
 * @package FastD\Routing
 */
class RouteCollection extends RouteRegex
{
    const ROUTES_CHUNK = 10;

    /**
     * @var Route
     */
    protected $activeRoute;

    /**
     * @var array
     */
    protected $staticRoutes = [];

    /**
     * @var array
     */
    protected $dynamicRoutes = [];

    /**
     * @var array
     */
    protected $map = [];

    protected $num = 0;

    protected $regexes = [];

    /**
     * @param $path
     * @return bool
     */
    protected function isStaticRoute($path)
    {
        return false === strpos($path, '{');
    }

    /**
     * @return array
     */
    public function getDynamicsMap()
    {
        return $this->dynamicRoutes;
    }

    /**
     * @return array
     */
    public function getStaticsMap()
    {
        return $this->staticRoutes;
    }

    /**
     * @param $method
     * @param $path
     * @param null $callback
     * @return $this
     */
    public function addRoute($method, $path, $callback)
    {
        if ($this->isStaticRoute($path)) {
            $this->staticRoutes[$method][$path] = $callback;
        } else {
            $routeInfo = $this->parseRoute($path);
            list($regex, $variables) = $this->buildRouteRegex($routeInfo);

            $numVariables = count($variables);
            $numGroups = max($this->num, $numVariables);
            $regexes[] = $regex . str_repeat('()', $numGroups - $numVariables);
            $routeMap[$numGroups + 1] = [];

            ++$this->num;

            $this->regexes[$method][] = $regex;
            $this->dynamicRoutes[$method]['regex'] = '~^(?|' . implode('|', $this->regexes[$method]) . ')$~';
            $this->dynamicRoutes[$method]['routes'][] = $callback;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @param $method
     * @param $path
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match($method, $path)
    {
        if (isset($this->staticRoutes[$method][$path])) {
            return $this->staticRoutes[$method][$path];
        }

        if (!isset($this->dynamicRoutes[$method])) {
            throw new RouteNotFoundException($path);
        }

        $quoteMap = $this->dynamicRoutes[$method];

        preg_match($quoteMap['regex'], $path, $matches);

        if (!empty($matches)) {
            return $quoteMap['routes'][count($matches)-1];
        }

        throw new RouteNotFoundException($path);
    }
}