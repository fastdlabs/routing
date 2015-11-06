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

class RouteGroup implements RouteGroupInterface
{
    protected $group;

    protected $func;

    protected $domain;

    protected $schema;

    public function __construct($group, $func)
    {
        $this->group = $group;

        $this->func = $func;
    }

    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    public function getSchema()
    {
        return $this->schema;
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
        // TODO: Implement current() method.
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
        // TODO: Implement next() method.
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
        // TODO: Implement key() method.
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
        // TODO: Implement valid() method.
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
        // TODO: Implement rewind() method.
    }

    public function getRoute($name)
    {
        // TODO: Implement getRoute() method.
    }

    public function setRoute(RouteInterface $routeInterface)
    {
        // TODO: Implement setRoute() method.
    }

    public function hasRoute($name)
    {
        // TODO: Implement hasRoute() method.
    }

    public function removeRoute($name)
    {
        // TODO: Implement removeRoute() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }

    public function getGroupName($group)
    {
        // TODO: Implement getGroupName() method.
    }

    public function setGroupName()
    {
        // TODO: Implement setGroupName() method.
    }

    public function setIp(array $ip)
    {
        // TODO: Implement setIp() method.
    }

    public function getIp()
    {
        // TODO: Implement getIp() method.
    }
}