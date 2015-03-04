<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午11:46
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

/**
 * Interface RouteInterface
 *
 * @package Dobee\Routing
 */
interface RouteInterface
{
    /**
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments);

    /**
     * @return string|array
     */
    public function getArguments();

    /**
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults);

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @param $route
     * @return $this
     */
    public function setRoute($route);

    /**
     * @return string
     */
    public function getRoute();

    /**
     * @param $pattern
     * @return $this
     */
    public function setPattern($pattern);

    /**
     * @return string
     */
    public function getPattern();

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format);

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param array $requirements
     * @return $this
     */
    public function setRequirements(array $requirements);

    /**
     * @return string|array
     */
    public function getRequirements();

    /**
     * @param $prefix
     * @return $this
     */
    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method);

    /**
     * @return string|array
     */
    public function getMethod();

    /**
     * @param $callable
     * @return $this
     */
    public function setCallable($callable);

    /**
     * @return mixed
     */
    public function getCallable();

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @return mixed
     */
    public function getParameters();
}