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

/**
 * Class Router
 *
 * @package FastD\Routing
 */
class Router
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @var array
     */
    protected $withPath = [];

    /**
     * @var RouteGroup[]
     */
    protected $withGroup = [];

    /**
     * @var RouteGroup
     */
    protected $group;

    protected $hashTable = [];

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function get($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['GET'], $default, $requirements, $formats);
    }

    public function post($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['POST'], $default, $requirements, $formats);
    }

    public function put($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['PUT'], $default, $requirements, $formats);
    }

    public function delete($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['DELETE'], $default, $requirements, $formats);
    }

    public function options($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['OPTIONS'], $default, $requirements, $formats);
    }

    public function head($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['HEAD'], $default, $requirements, $formats);
    }

    public function patch($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['PATCH'], $default, $requirements, $formats);
    }

    public function trace($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['TRACE'], $default, $requirements, $formats);
    }

    public function match(array $method = ['GET'], $path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, $method, $default, $requirements, $formats);
    }

    public function any($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['ANY'], $default, $requirements, $formats);
    }

    public function with($path, $name, \Closure $callback = null)
    {
        $this->withPath[] = $path;

        $this->withGroup[] = new RouteGroup($path, $name, $callback);

        $this->group = end($this->withGroup);

        $callback($this);

        $this->hashTable = array_merge($this->hashTable, $this->group->getHashTable());

        array_pop($this->withPath);
        array_pop($this->withGroup);

        $group = $this->group;

        $this->group = null;

        return $group;
    }

    /**
     * @param $path
     * @param $callback
     * @param $name
     * @param $methods
     * @param $default
     * @param $requirements
     * @param $formats
     * @return RouteInterface
     */
    public function createRoute($path, $name, $callback = null, array $methods = ['GET'], $default = [], $requirements = [], $formats = ['php'])
    {
        $group = implode('', $this->withPath);

        $route = new Route($group . $path, $name, $callback, $methods, $default, $requirements, $formats);

        null !== $this->group ? $this->group->setRoute($route) : null;

        $this->appendHashTable($route);

        return $route;
    }

    public function appendHashTable(RouteInterface $routeInterface)
    {
        $this->hashTable[$routeInterface->getPath()] = $routeInterface->getName();

        $this->routes[$routeInterface->getName()] = $routeInterface;

        return $this;
    }

    public function dispatch($route)
    {

    }
}
