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
     * @return array
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
     * @return string
     */
    public function getPathRegex();

    /**
     * @param array $format
     * @return $this
     */
    public function setFormat(array $format);

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
     * @param array $method
     * @return $this
     */
    public function setMethod(array $method);

    /**
     * @return string|array
     */
    public function getMethod();

    /**
     * @param $callback
     * @return $this
     */
    public function setCallback($callback);

    /**
     * @return mixed
     */
    public function getCallback();

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @return string|array
     */
    public function getParameters();
}