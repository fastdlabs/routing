<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/28
 * Time: 下午7:10
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Routing;

use FastD\Routing\Matcher\RouteMatcher;
use FastD\Routing\Exception\RouteException;

/**
 * Class Router
 *
 * @package FastD\Routing
 */
class Router extends RouteCollection
{
    /**
     * @var Route
     */
    protected $routeProperty;

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $group = [];

    /**
     * @var RouteMatcher
     */
    protected $matcher;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routeProperty = new Route(null, null);

        $this->matcher = new RouteMatcher($this);
    }

    /**
     * @param null  $name
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @param array $methods
     * @param array $schemas
     * @param null  $host
     * @return Route
     */
    public function addRoute($name = null, $path, $callback, array $defaults = [], array $requirements = [], array $methods = [], array $schemas = ['http'], $host = null)
    {
        $route = clone $this->routeProperty;
        $with = implode('', $this->with);
        $path = str_replace('//', '/', $with . $path);
        $route->setDefaults($defaults);
        $route->setRequirements($requirements);
        $route->setMethods($methods);
        $route->setPath($path);
        $route->setName($name);
        $route->setRouteWith($with);
        $route->setCallback($callback);
        $route->setSchema($schemas);
        $route->setHost($host);
        $this->setRoute($route);
        $this->group[$with][] = $name;
        return $route;
    }

    /**
     * Get route collections.
     *
     * @return RouteCollection
     */
    public function getCollection()
    {
        return $this;
    }

    /**
     * @param          $path
     * @param \Closure $callback
     */
    public function with($path, \Closure $callback)
    {
        array_push($this->with, $path);

        $callback($this);

        array_pop($this->with);
    }

    /**
     * @param $name
     * @return bool|\Closure
     */
    public function dispatch($name)
    {
        return $this->getRoute($name)->getCallback();
    }

    /**
     * @param $path
     * @return Route
     * @throws Exception\RouteException
     */
    public function match($path)
    {
        return RouteMatcher::match($path, $this);
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param null  $format
     * @return string
     * @throws Exception\RouteException
     * @throws RouteException
     */
    public function generateUrl($name, array $parameters = [], $format = null)
    {
        return RouteGenerator::generateUrl($this->getRoute($name), $parameters, $format);
    }
}
