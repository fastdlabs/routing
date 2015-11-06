<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/6
 * Time: 下午6:54
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

interface RouteCollectionInterface extends \Countable, \Iterator
{
    public function getGroupRoute($group);

    public function setGroupRoute(RouteGroup $routeGroup);

    public function setRoute(RouteInterface $routeInterface);

    public function hasRoute($name);

    public function removeRoute($name);

    public function getRoute($name);

    public function setCurrentRoute(RouteInterface $routeInterface);

    public function getCurrentRoute();
}