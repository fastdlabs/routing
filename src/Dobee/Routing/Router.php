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

namespace Dobee\Routing;

use Dobee\Routing\Generator\RouteGenerator;
use Dobee\Routing\Matcher\RouteMatcher;

/**
 * Class Router
 *
 * @package Dobee\Routing
 */
class Router
{
    /**
     * @var RouteCollections
     */
    private $collections;

    /**
     * @var string
     */
    private $group = '';

    /**
     * Router constructor.
     * Initialize route collections and route Generator.
     */
    public function __construct()
    {
        $this->collections = new RouteCollections();
    }

    /**
     * @return RouteCollections
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * @param       $name
     * @param array $parameters
     * @param bool $suffix
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $suffix = false)
    {
        return RouteGenerator::generateUrl($this->collections->getRoute($name), $parameters, $suffix);
    }

    /**
     * @param RouteInterface $routeInterface
     * @return $this
     */
    public function setRoute(RouteInterface $routeInterface = null)
    {
        return $this->collections->setRoute($routeInterface);
    }

    /**
     * @param $name
     * @return RouteInterface
     * @throws RouteException
     */
    public function getRoute($name)
    {
        return $this->collections->getRoute($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRoute($name)
    {
        return $this->collections->hasRoute($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function removeRoute($name)
    {
        return $this->collections->removeRoute($name);
    }

    /**
     * @param $route
     * @param $callback
     * @param $method
     * @return Route
     */
    public function createRoute($route, $callback, $method)
    {
        $name = '';

        if (is_array($route)) {
            $name = isset($route['name']) ? $route['name'] : '';
            $route = $route[0];
        }

        $route = new Route($route, $name, array(), array($method), array(), array(), $callback);

        $this->setRoute($route);

        unset($name);

        return $route;
    }

    /**
     * @param $group
     * @param $closure
     * @return void
     */
    public function group($group, $closure)
    {
        if (!is_callable($closure)) {
            throw new \InvalidArgumentException(sprintf('Argument 2 must be a Closure.'));
        }

        $this->group = $group;

        $closure();

        $this->group = '';
    }

    /**
     * @param  string $path
     * @return Route
     */
    public function match($path)
    {
        return RouteMatcher::match($path, $this->collections);
    }

    /**
     * @param string         $path
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchRoute($path, RouteInterface $route)
    {
        return RouteMatcher::matchRequestRoute($path, $route);
    }

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return bool
     * @throws RouteException
     */
    public function matchMethod($method, RouteInterface $route)
    {
        return RouteMatcher::matchRequestMethod($method, $route);
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return bool
     * @throws RouteException
     */
    public function matchFormat($format, RouteInterface $route)
    {
        return RouteMatcher::matchRequestFormat($format, $route);
    }

    /**
     * @param RouteInterface $route
     * @return $this
     */
    public function setCurrentRoute(RouteInterface $route)
    {
        $this->collections->setCurrentRoute($route);

        return $this;
    }

    /**
     * @return RouteInterface
     */
    public function getCurrentRoute()
    {
        return $this->collections->getCurrentRoute();
    }
}