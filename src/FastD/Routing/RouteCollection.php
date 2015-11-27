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

use FastD\Routing\Exception\RouteException;

class RouteCollection implements RouteCollectionInterface
{
    protected $routes = [];

    protected $map = [];

    protected $current;

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

        return $this->routes[$this->current];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRoute($name)
    {
        if (isset($this->map[$name])) {
            $this->current = $this->map[$name];
            return true;
        }

        if (isset($this->routes[$name])) {
            $this->current = $name;
            return true;
        }

        return false;
    }

    /**
     * @param Route $route
     * @return $this
     */
    public function setRoute(Route $route)
    {
        $this->map[$route->getName()] = $route->getPath();
        $this->routes[$route->getPath()] = $route;

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
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return Route
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
     * @return string
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->routes);
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
        return isset($this->routes[$this->key()]);
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