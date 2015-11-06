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

use FastD\Routing\Collection\RouteCollectionInterface;

/**
 * Interface RouteInterface
 *
 * @package FastD\Routing
 */
interface RouteInterface
{
    /**
     * Return route schema: http or https.
     *
     * {@inheritdoc}
     * @return string
     */
    public function getSchema();

    /**
     * {@inheritdoc}
     * @param $schema
     * @return RouteInterface
     */
    public function setSchema($schema);

    /**
     * {@inheritdoc}
     * @param $domain
     * @return RouteInterface
     */
    public function setDomain($domain);

    /**
     * Return route setting domain. Examples, "www.google.com". Default NULL.
     * {@inheritdoc}
     * @return string
     */
    public function getDomain();

    /**
     * Setting this route access ips.
     * {@inheritdoc}
     * @param array $ips
     * @return RouteInterface
     */
    public function setIps(array $ips);

    /**
     * Return setting access ip list.
     * {@inheritdoc}
     * @return array
     */
    public function getIps();

    /**
     * Setting route access date expire.
     * {@inheritdoc}
     * @param RouteExpire $routeExpire
     * @return RouteInterface
     */
    public function setExpire(RouteExpire $routeExpire);

    /**
     * @return RouteExpire
     */
    public function getExpire();

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
     * @param array $defaults
     * @return RouteInterface
     */
    public function setDefaults(array $defaults);

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @param $route
     * @return RouteInterface
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
     * @return RouteInterface
     */
    public function setFormats(array $format);

    /**
     * @return array
     */
    public function getFormats();

    /**
     * @param array $requirements
     * @return RouteInterface
     */
    public function setRequirements(array $requirements);

    /**
     * @return array
     */
    public function getRequirements();

    /**
     * @param array $method
     * @return RouteInterface
     */
    public function setMethods(array $method);

    /**
     * @return array
     */
    public function getMethods();

    /**
     * @param $callback
     * @return RouteInterface
     */
    public function setCallback($callback);

    /**
     * @return \Closure
     */
    public function getCallback();

    /**
     * @return RouteCollectionInterface
     */
    public function getGroup();

    /**
     * @param RouteCollectionInterface $routeCollectionInterface
     * @return $this
     */
    public function setGroup(RouteCollectionInterface $routeCollectionInterface);
}