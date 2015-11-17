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

use FastD\Routing\Exception\RouteException;

/**
 * Class Router
 *
 * @package FastD\Routing
 */
class Router
{
    protected $routeProperty;

    /**
     * @var Route[]
     */
    protected $routes;

    protected $name;

    /**
     * @var array
     */
    protected $with = [];

    public function __construct()
    {
        $this->routeProperty = new Route(null, null);
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->routes;
    }

    public function get($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['GET'], $schemas, $host);
    }

    public function post($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['POST'], $schemas, $host);
    }

    public function put($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['PUT'], $schemas, $host);
    }

    public function delete($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['DELETE'], $schemas, $host);
    }

    public function head($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['HEAD'], $schemas, $host);
    }

    public function patch($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['PATCH'], $schemas, $host);
    }

    public function options($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['OPTIONS'], $schemas, $host);
    }

    public function trace($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['TRACE'], $schemas, $host);
    }

    public function any($path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, ['ANY'], $schemas, $host);
    }

    public function match($methods, $path, $callback, array $defaults = [], array $requirements = [], array $schemas = ['http'], $host = null)
    {
        return $this->createRoute($path, $callback, $defaults, $requirements, explode('|', $methods), $schemas, $host);
    }

    public function with($path, \Closure $callback, \Closure $init = null)
    {
        array_push($this->with, $path);

        $callback($this);

        array_pop($this->with);
    }

    public function createRoute($path, $callback, array $defaults = [], array $requirements = [], array $methods = [], array $schemas = ['http'], $host = null)
    {
        $route = clone $this->routeProperty;
        $path = implode('', $this->with) . $path;
        $route->setDefaults($defaults);
        $route->setRequirements($requirements);
        $route->setMethods($methods);
        $route->setPath($path);
        $route->setName($path);
        $route->setCallback($callback);
        $route->setSchema($schemas);
        $route->setHost($host);
        $this->setRoute($route);
        $this->name = null;
        return $route;
    }

    /**
     * @param $name
     * @return Route
     * @throws RouteException
     */
    public function getRoute($name)
    {
        if (!$this->hasRoute($name)) {
            throw new RouteException(sprintf('Route "%s" is not exists.', $name));
        }

        return $this->routes[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRoute($name)
    {
        return isset($this->routes[$name]) ? true : false;
    }

    public function setRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function removeRoute($name)
    {
        if ($this->hasRoute($name)) {
            unset($this->routes[$name]);
        }

        return true;
    }

    /**
     * @param $name
     * @return bool|\Closure
     */
    public function dispatch($name)
    {
        return $this->getRoute($name)->getCallback();
    }
}
