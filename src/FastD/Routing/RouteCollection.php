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
class RouteCollection implements \Iterator, \Countable
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $map = [];

    /**
     * @var Route
     */
    protected $current;

    /**
     * @var string
     */
    protected $index;

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
        $alias = $route->getPath() . ':' . strtolower($route->getMethod());
        $this->map[$route->getName()] = $alias;
        $this->routes[$alias] = $route;
        $this->current = $route;

        unset($alias, $route);

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function removeRoute($name): bool
    {
        if ($this->hasRoute($name)) {
            unset($this->routes[$name]);
        }

        return true;
    }

    /**
     * @return Route
     */
    public function getCurrentRoute(): Route
    {
        return $this->current;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return Route
     * @since 5.0.0
     */
    public function current(): Route
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