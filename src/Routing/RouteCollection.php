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
abstract class RouteCollection
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
     * @param $path
     * @return array
     */
    protected function parseRoute($path)
    {
        return preg_split('/(\/[^\/]+)/', $path, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    /**
     * @return array
     */
    public function getDynamics()
    {
        return $this->dynamicRoutes;
    }

    /**
     * @return array
     */
    public function getStatics()
    {
        return $this->staticRoutes;
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
            $routeInfo = $this->parseRoute($route->getPath());

            $quoteMap = $this->dynamicRoutes[$route->getMethod()] ?? [];

            (function ($routeInfo) use (&$quoteMap, $route) {
                foreach ($routeInfo as $key) {
                    if (isset($quoteMap[$key])) {
                        $quoteMap = & $quoteMap[$key];
                    } else {
                        $quoteMap[$key] = [];
                        $quoteMap = & $quoteMap[$key];
                    }
                }
                $quoteMap = $route;
            }) ($routeInfo);

            $this->dynamicRoutes[$route->getMethod()] = $quoteMap;

            unset($quoteMap, $routeInfo, $route);
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