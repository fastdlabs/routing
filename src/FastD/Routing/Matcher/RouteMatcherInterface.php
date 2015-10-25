<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 下午2:31
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Routing\Matcher;

use FastD\Routing\RouteInterface;
use FastD\Routing\RouteCollections;

/**
 * Interface RouteMatcherInterface
 *
 * @package FastD\Routing\Matcher
 */
interface RouteMatcherInterface
{
    /**
     * Match base request url from route collection.
     *
     * {@inheritdoc}
     * @param                $url
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchUrl($url, RouteInterface $route = null);

    /**
     * {@inheritdoc}
     * @param                $method
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchMethod($method, RouteInterface $route = null);

    /**
     * {@inheritdoc}
     * @param                $format
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchFormat($format, RouteInterface $route = null);

    /**
     * {@inheritdoc}
     * @param                $ips
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchRequestIps($ips, RouteInterface $route = null);
}