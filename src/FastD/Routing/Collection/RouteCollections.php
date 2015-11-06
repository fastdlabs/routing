<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午1:51
 */

namespace FastD\Routing\Collection;

use FastD\Routing\Exception\RouteException;
use FastD\Routing\RouteInterface;

/**
 * Class RouteCollections
 *
 * @package FastD\Routing
 */
class RouteCollections implements \Iterator, \Countable
{
    /**
     * @var RouteInterface[]
     */
    protected $routes = array();

    /**
     * The route alias maps.
     *
     * @var array
     */
    protected $alias = array();

    /**
     * @var RouteInterface
     */
    protected $currentRoute;

    /**
     * @return RouteInterface
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * @param RouteInterface $currentRoute
     * @return $this
     */
    public function setCurrentRoute(RouteInterface $currentRoute)
    {
        $this->currentRoute = $currentRoute;

        return $this;
    }

    /**
     * @param RouteInterface $route
     * @return $this
     */
    public function setRoute(RouteInterface $route = null)
    {
        $this->routes[$route->getName()] = $route;

        $this->alias[$route->getPath()] = $route->getName();

        return $this;
    }

    /**
     * @param $name
     * @return RouteInterface
     * @throws RouteException
     */
    public function getRoute($name)
    {
        if (!$this->hasRoute($name)) {
            if (!isset($this->alias[$name])) {
                throw new RouteException(sprintf('Route "%s" is not found.', $name));
            }
            $name = $this->alias[$name];
        }

        return $this->routes[$name];
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function hasRoute($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function removeRoute($name)
    {
        if ($this->hasRoute($name)) {
            unset($this->routes[$name]);
        }

        return true;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return Route
     */
    public function current()
    {
        return current($this->routes);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->routes);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->routes);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->routes[$this->key()]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->routes);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->routes);
    }
}

