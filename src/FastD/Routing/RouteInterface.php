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

namespace FastD\Routing;

/**
 * Interface RouteInterface
 *
 * @package FastD\Routing
 */
interface RouteInterface
{
    /**
     * @return string
     */
    public function getHttpProtocol();

    /**
     * @param $httpProtocol
     * @return $this
     */
    public function setHttpProtocol($httpProtocol);

    /**
     * @param array $domain
     * @return $this
     */
    public function setDomain($domain);

    /**
     * @return array
     */
    public function getDomain();

    /**
     * @param array $ips
     * @return $this
     */
    public function setIps(array $ips);

    /**
     * @return array
     */
    public function getIps();

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
    public function setPath($route);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function getPathRegex();

    /**
     * @param array $format
     * @return $this
     */
    public function setFormats(array $format);

    /**
     * @return string
     */
    public function getFormats();

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
    public function setMethods(array $method);

    /**
     * @return string|array
     */
    public function getMethods();

    /**
     * @param $callback
     * @return $this
     */
    public function setCallback($callback);

    /**
     * @return \Closure
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

    /**
     * @param $group
     * @return $this
     */
    public function setGroup($group);

    /**
     * @return string
     */
    public function getGroup();
}