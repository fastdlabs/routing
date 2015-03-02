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
<<<<<<< HEAD
     * @return string
     */
    public function getClassName();
=======
     * @param $class
     * @return $this
     */
    public function setClass($class);

    /**
     * @return string
     */
    public function getClass();

    public function setAction($action);
>>>>>>> master

    /**
     * @return string
     */
    public function getAction();

<<<<<<< HEAD
=======
    public function setRoute($route);

>>>>>>> master
    /**
     * @return string
     */
    public function getRoute();

<<<<<<< HEAD
=======
    public function setName($name);

>>>>>>> master
    /**
     * @return string
     */
    public function getName();

<<<<<<< HEAD
=======
    public function setPrefix($prefix);

>>>>>>> master
    /**
     * @return string
     */
    public function getPrefix();

<<<<<<< HEAD
=======
    public function setMethod($method);

>>>>>>> master
    /**
     * @return string
     */
    public function getMethod();

<<<<<<< HEAD
=======
    public function setDefaults(array $defaults);

>>>>>>> master
    /**
     * @return array
     */
    public function getDefaults();

<<<<<<< HEAD
=======
    public function setRequirements(array $requirements);

>>>>>>> master
    /**
     * @return array
     */
    public function getRequirements();

<<<<<<< HEAD
=======
    public function setFormat($format);

>>>>>>> master
    /**
     * @return string
     */
    public function getFormat();

<<<<<<< HEAD
=======
    public function setArguments(array $arguments);

>>>>>>> master
    /**
     * @return array
     */
    public function getArguments();

<<<<<<< HEAD
=======
    public function setParameters(array $parameters);

>>>>>>> master
    /**
     * @return array
     */
    public function getParameters();
<<<<<<< HEAD
=======

    public function setPattern($pattern);

    /**
     * @return string
     */
    public function getPattern();

    public function setCallable($callable);

    public function getCallable();
>>>>>>> master
}