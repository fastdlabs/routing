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

    public function getRoute($name)
    {

    }

    /**
     * @param Route $route
     * @return RouteCollection
     */
    public function setRoute(Route $route): RouteCollection
    {
        if ($this->isStaticRoute($route->getPath())) {
            $this->staticRoutes[$route->getMethod()][$route->getPath()] = $route;
        } else {
            $regex = $this->parseRoute($route->getPath());
            // '~^(?|' . implode('|', $regexes) . ')$~';
            $this->dynamicRoutes[$route->getMethod()]['regex'] = $regex;
            $this->dynamicRoutes[$route->getMethod()]['routes'][] = $route;
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
}