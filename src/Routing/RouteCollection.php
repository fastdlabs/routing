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
     * @param $callback
     * @param null $name
     * @return $this
     */
    public function addRoute($method, $path, $callback, $name = null)
    {
        if ($this->isStaticRoute($path)) {
            $this->staticRoutes[$method][$path] = new Route(null === $name ? $path : $name, $method, $path, null, $callback, [], []);
        } else {
            $routeInfo = $this->parseRoute($path);
            list($regex, $variables) = $this->buildRouteRegex($routeInfo);

            $numVariables = count($variables);
            $numGroups = max($this->num, $numVariables);
            $this->regexes[$method][] = $regex . str_repeat('()', $numGroups - $numVariables);

            $this->dynamicRoutes[$method][$this->index]['regex'] = '~^(?|' . implode('|', $this->regexes[$method]) . ')$~';
            $this->dynamicRoutes[$method][$this->index]['routes'][$numGroups + 1] = new Route(null === $name ? $path : $name, $method, $path, $regex, $callback, [], []);

            ++$this->num;

            if (count($this->regexes[$method]) >= static::ROUTES_CHUNK) {
                ++$this->index;
                $this->num = 1;
                $this->regexes[$method] = [];
            }
            unset($numGroups, $numVariables);
        }

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
        if (isset($this->staticRoutes[$method][$path])) {
            return $this->staticRoutes[$method][$path];
        }

        if (!isset($this->dynamicRoutes[$method])) {
            throw new RouteNotFoundException($path);
        }

        $quoteMap = $this->dynamicRoutes[$method];

        foreach ($quoteMap as $data) {

            if (!preg_match($data['regex'], $path, $matches)) {
                continue;
            }

            return $data['routes'][count($matches)];
        }

        throw new RouteNotFoundException($path);
    }
}