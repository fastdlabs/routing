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
class Router implements RouteCollectionInterface
{
    /**
     * @var RouteInterface[]
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

    /**
     * @var array
     */
    protected $hashTable = [];

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function get($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['GET'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function post($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['POST'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function put($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['PUT'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function delete($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['DELETE'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function options($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['OPTIONS'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function head($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['HEAD'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function patch($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['PATCH'], $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function trace($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['TRACE'], $default, $requirements, $formats);
    }

    /**
     * @param array $method
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function match(array $method = ['GET'], $path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, $method, $default, $requirements, $formats);
    }

    /**
     * @param       $path
     * @param       $name
     * @param null  $callback
     * @param array $default
     * @param array $requirements
     * @param array $formats
     * @return RouteInterface
     */
    public function any($path, $name, $callback = null, array $default = [], array $requirements = [], array $formats = [])
    {
        return $this->createRoute($path, $name, $callback, ['ANY'], $default, $requirements, $formats);
    }

    /**
     * @param               $path
     * @param               $name
     * @param \Closure|null $callback
     * @return RouteGroup
     */
    public function with($path, $name, \Closure $callback = null)
    {
        $this->withPath[] = $path;

        if (!isset($this->withGroup[$name])) {
            $this->withGroup[$name] = new RouteGroup(implode('', $this->withPath), $name, $callback);
        }

        $this->group = $this->withGroup[$name];

        if (is_callable($callback)) {
            $callback($this);
        }

        array_pop($this->withPath);

        $this->group = null;

        return $this->withGroup[$name];
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

    /**
     * @param RouteInterface $routeInterface
     * @return $this
     */
    public function appendHashTable(RouteInterface $routeInterface)
    {
        $this->hashTable[$routeInterface->getPath()] = $routeInterface->getName();

        $this->routes[$routeInterface->getName()] = $routeInterface;

        return $this;
    }

    /**
     * @param $name
     * @return bool|RouteGroup
     */
    public function getWithRoute($name)
    {
        return isset($this->withGroup[$name]) ? $this->withGroup[$name] : false;
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

    /**
     * @param RouteInterface $routeInterface
     * @return $this
     */
    public function setRoute(RouteInterface $routeInterface)
    {
        $this->routes[$routeInterface->getName()] = $routeInterface;

        $this->appendHashTable($routeInterface);

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

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->routes);
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->routes);
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return current($this->routes);
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->routes[$this->key()]) ? true : false;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->routes);
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *        </p>
     *        <p>
     *        The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->routes);
    }
}
