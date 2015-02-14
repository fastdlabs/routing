<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 下午1:29
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

/**
 * Interface RouteCollectionInterface
 *
 * @package Dobee\Routing
 */
interface RouteCollectionInterface
{
    /**
     * @return mixed
     */
    public function getRoutes();

    /**
     * @return RouteInterface
     */
    public function getRoute($route_name);

    /**
     * @param RouteInterface $routeInterface
     * @return mixed
     */
    public function addRoute(RouteInterface $routeInterface);

    /**
     * @param $name
     * @return mixed
     */
    public function removeRoute($name);

    /**
     * @param $name
     * @return mixed
     */
    public function hasRoute($name);

    /**
     * @param       $name
     * @param array $parameters
     * @return mixed
     */
    public function generateUrl($name, array $parameters = array());
}