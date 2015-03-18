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

namespace Dobee\Routing\Matcher;

use Dobee\Routing\RouteInterface;
use Dobee\Routing\RouteCollectionInterface;

/**
 * Interface RouteMatcherInterface
 *
 * @package Dobee\Routing\Matcher
 */
interface RouteMatcherInterface
{
    /**
     * @param                $uri
     * @param RouteCollectionInterface $collections
     * @return mixed
     */
    public function match($uri, RouteCollectionInterface $collections = null);

    /**
     * @param                $uri
     * @param RouteInterface $route
     * @return mixed
     */
    public function matchRequestRoute($uri, RouteInterface $route);

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return mixed
     */
    public function matchRequestMethod($method, RouteInterface $route);

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return mixed
     */
    public function matchRequestFormat($format, RouteInterface $route);
}