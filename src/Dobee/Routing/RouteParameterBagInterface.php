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

    public function setAction($action);

    /**
     * @return string
     */
    public function getAction();

    public function setRoute($route);

    /**
     * @return string
     */
    public function getRoute();

    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function getPrefix();

    public function setMethod($method);

    /**
     * @return string
     */
    public function getMethod();

    public function setDefaults(array $defaults);

    /**
     * @return array
     */
    public function getDefaults();

    public function setRequirements(array $requirements);

    /**
     * @return array
     */
    public function getRequirements();

    public function setFormat($format);

    /**
     * @return string
     */
    public function getFormat();

    public function setArguments(array $arguments);

    /**
     * @return array
     */
    public function getArguments();

    public function setParameters(array $parameters);

    /**
     * @return array
     */
    public function getParameters();

    public function setPattern($pattern);

    /**
     * @return string
     */
    public function getPattern();

    public function setCallable($callable);

    public function getCallable();
}