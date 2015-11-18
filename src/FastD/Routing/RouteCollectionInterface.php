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

/**
 * Interface RouteCollectionInterface
 *
 * @package FastD\Routing
 */
interface RouteCollectionInterface extends \Countable, \Iterator
{
    /**
     * @param Route $route
     * @return RouteCollectionInterface
     */
    public function setRoute(Route $route);

    /**
     * @param $name
     * @return RouteCollectionInterface
     */
    public function hasRoute($name);

    /**
     * @param $name
     * @return bool
     */
    public function removeRoute($name);

    /**
     * @param $name
     * @return RouteInterface
     */
    public function getRoute($name);
}