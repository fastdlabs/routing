<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 下午1:25
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

abstract class RouteAbstract implements RouteInterface/*, \Countable*/
{
    /*private $parameters = array();

    public function removeParameters($name)
    {
        if ($this->hasParameters($name)) {
            unset($this->parameters[$name]);
        }

        return $this;
    }

    public function addParameters($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function hasParameters($name)
    {
        return isset($this->parameters[$name]);
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters($name)
    {
        if (!$this->hasParameters($name)) {
            throw new \InvalidArgumentException(sprintf("%s' is undefined.", $name));
        }

        return $this->parameters[$name];
    }*/

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    /*public function count()
    {
        return count($this->_parameters);
    }*/
}