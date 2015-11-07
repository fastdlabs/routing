<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 下午11:22
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

use FastD\Routing\Exception\RouteException;

/**
 * Class RouteGroup
 *
 * @package FastD\Routing
 */
class RouteGroup extends Route implements RouteCollectionInterface
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $hashTable = [];

    /**
     * @return array
     */
    public function getHashTable()
    {
        return $this->hashTable;
    }

    public function getRoutes()
    {
        return $this->routes;
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
     * Reinitialize route.
     *
     * @param RouteInterface $routeInterface
     * @return $this
     */
    public function setRoute(RouteInterface $routeInterface)
    {
        $routeInterface->setGroup($this);

//        $routeInterface->setSchema($this->getSchema());
//        $routeInterface->setPath($this->getPath() . $routeInterface->getPath());
//        $routeInterface->setDomain($this->getDomain());
//        $routeInterface->setExpire($this->getExpire());
//        $routeInterface->setMethods($this->getMethods());
//        $routeInterface->setIps(array_merge($this->getIps(), $routeInterface->getIps()));
//        $routeInterface->parsePathRegex($routeInterface->getPath(), $routeInterface->getRequirements());

        $this->hashTable[$routeInterface->getPath()] = $routeInterface->getName();
        $this->routes[$routeInterface->getName()] = $routeInterface;

        return $this;
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
     * @param $name
     * @return $this
     */
    public function removeRoute($name)
    {
        if ($this->hasRoute($name)) {
            unset($this->routes[$name]);
        }

        return $this;
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
     * @return mixed scalar on success, or null on failure.
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

    /**
     * Returns if an iterator can be created for the current entry.
     *
     * @link  http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     * @since 5.1.0
     */
    public function hasChildren()
    {
        // TODO: Implement hasChildren() method.
    }

    /**
     * Returns an iterator for the current entry.
     *
     * @link  http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator An iterator for the current entry.
     * @since 5.1.0
     */
    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }
}