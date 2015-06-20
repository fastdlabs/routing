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

use FastD\Routing\Generator\RouteGenerator;
use FastD\Routing\Matcher\RouteMatcher;

/**
 * Class Router
 *
 * @package FastD\Routing
 */
class Router
{
    /**
     * @var RouteCollections
     */
    private $collections;

    /**
     * Full group name.
     *
     * @var string
     */
    private $group = [];

    /**
     * @var string
     */
    private $protocol = 'http';

    /**
     * Route group domain.
     *
     * @var string
     */
    private $domain = '';

    /**
     * Domain group name
     *
     * @var string
     */
    private $domainGroup = [];

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
     * @param $methods
     * @return Route
     */
    public function createRoute($route, $callback, $methods)
    {
        $name = '';

        $group = implode('', $this->group);

        $routeName = $route;

        if (is_array($route)) {
            $name = isset($route['name']) ? $route['name'] : '';
            $routeName = $route[0];
            $route = $group . $route[0];
        } else if (is_string($route)) {
            $routeName = $route;
            $route = str_replace('//', '/', $group . $route);
            $name = $route;
        }

        $route = new Route($route, $name, array(), $methods, array(), array(), $callback);

        $route
            ->setPath(implode('', $this->domainGroup) . $routeName)
            ->setGroup($group)
            ->setHttpProtocol($this->protocol)
            ->setDomain($this->domain)
        ;

        $this->setRoute($route);

        unset($name, $routeName);

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

        $groupInfo = $group;

        if (is_array($groupInfo)) {
            foreach ($groupInfo as $name => $value) {
                if (isset($this->$name)) {
                    $this->$name = $value;
                }
            }

            if (isset($groupInfo[0])) {
                $group = $groupInfo[0];
            }
        }

        $this->group[] = $group;

        if (!isset($groupInfo['domain'])) {
            $this->domainGroup[] = $group;
        }

        unset($group);

        $closure();

        array_pop($this->group);
        array_pop($this->domainGroup);
        $this->domain = '';
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
     * @param                $host
     * @param RouteInterface $route
     * @return bool
     * @throws RouteException
     */
    public function matchHost($host, RouteInterface $route)
    {
        return RouteMatcher::matchRequestHost($host, $route);
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
     * @param                $ip
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchIp($ip, RouteInterface $route)
    {
        return RouteMatcher::matchRequesetIps($ip, $route);
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