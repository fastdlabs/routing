<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午1:51
 */

namespace Dobee\Routing;

/**
 * Class RouteCollections
 *
 * @package Dobee\Component\Routing\Collections
 */
class RouteCollections implements RouteCollectionInterface, \Iterator, \Countable
{
    /**
     * @var string
     */
    protected $separator = '@';

    /**
     * @var array
     */
    private $routeCollections = array();

    /**
     * @var Route
     */
    protected $currentRoute;

    /**
     * @return Route
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * @param Route $currentRoute
     * @return $this
     */
    public function setCurrentRoute(Route $currentRoute)
    {
        $this->currentRoute = $currentRoute;

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->routeCollections);
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
        next($this->routeCollections);
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
        return key($this->routeCollections);
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
        return isset($this->routeCollections[$this->key()]);
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
        reset($this->routeCollections);
    }

    /**
     * @param                $name
     * @param RouteInterface $routeInterface
     * @return $this
     * @throws RouteInvalidException
     * @throws \Exception
     */
    public function setRoute($name, RouteInterface $routeInterface = null)
    {
        if ($name instanceof RouteInterface && null === $routeInterface) {
            if (null === $name->getName()) {
                throw new RouteInvalidException(sprintf("Route '%s' route name is empty or null.", get_class($name)));
            }

            $routeInterface = $name;

            $name = $name->getName();
        }

        if (array_key_exists($name, $this->routeCollections)) {
            throw new \Exception(sprintf("Route name '%s' is exists.", $name));
        }

        $this->routeCollections[$name] = $routeInterface;

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws RouteNotFoundException
     */
    public function getRoute($name)
    {
        if (!$this->hasRoute($name)) {
            throw new RouteNotFoundException(sprintf('Route "%s" is not found.', $name));
        }

        return $this->routeCollections[$name];
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function hasRoute($name)
    {
        return isset($this->routeCollections[$name]);
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function removeRoute($name)
    {
        if ($this->hasRoute($name)) {
            unset($this->routeCollections[$name]);
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getRouteCollections()
    {
        return $this->routeCollections;
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
        return count($this->routeCollections);
    }
}