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

interface RouteCollectionInterface
{
    public function getRoutes();

    public function getRoute($route_name);

    public function addRoute(RouteInterface $routeInterface);

    public function removeRoute($name);

    public function hasRoute($name);
}