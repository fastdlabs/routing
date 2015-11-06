<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/6
 * Time: 下午10:14
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

/**
 * Interface RouteGroupInterface
 *
 * @package FastD\Routing
 */
interface RouteGroupInterface extends RouteInterface, \Countable, \Iterator
{
    public function getRoute($name);

    public function setRoute(RouteInterface $routeInterface);

    public function hasRoute($name);

    public function removeRoute($name);
}