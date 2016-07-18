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
     * 用户快速匹配路由, 用于 has, get 方法
     *
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
     * @param $name
     * @return Route
     * @throws \Exception
     */
    public function getRoute($name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new \Exception(sprintf('Route "%s" is not exists.', $name));
        }

        return $this->routes[$this->index];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRoute($name): bool
    {
        if (isset($this->map[$name])) {
            $this->index = $this->map[$name];
            return true;
        }

        if (isset($this->routes[$name])) {
            $this->index = $name;
            return true;
        }

        return false;
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
            // /test/123
            /*
             * [
             *  '/test' => [
             *      '/123' => $route
             *  ]
             * ]
             * */
            $this->dynamicRoutes[$route->getMethod()][] = $route;
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