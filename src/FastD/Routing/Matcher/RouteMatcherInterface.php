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

use FastD\Routing\RouteCollectionInterface;

/**
 * Interface RouteMatcherInterface
 *
 * @package FastD\Routing\Matcher
 */
interface RouteMatcherInterface
{
    public static function match(
        $path,
        $method,
        $format,
        $host,
        $scheme,
        $ip,
        RouteCollectionInterface $routeCollectionInterface
    );
}