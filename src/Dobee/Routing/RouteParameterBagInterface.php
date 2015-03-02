<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午4:42
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing;

/**
 * Interface RouteParameterBagInterface
 *
 * @package Dobee\Routing
 */
interface RouteParameterBagInterface
{
    /**
     * @param $class
     * @return $this
     */
    public function setClass($class);

    /**
     * @return string
     */
    public function getClass();

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action);

    /**
     * @return string
     */
    public function getAction();

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
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

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
     * @return string
     */
    public function getMethod();

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
     * @param array $requirements
     * @return $this
     */
    public function setRequirements(array $requirements);

    /**
     * @return array
     */
    public function getRequirements();

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
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @return array
     */
    public function getParameters();

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
     * @param $callable
     * @return $this
     */
    public function setCallable($callable);

    /**
     * @return $this
     */
    public function getCallable();
}