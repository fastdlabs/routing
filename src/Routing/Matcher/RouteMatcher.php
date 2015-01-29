<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 下午2:32
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing\Matcher;

use Dobee\Routing\RouteInterface;

class RouteMatcher implements RouteMatcherInterface
{
    public function match($uri, RouteInterface $routeInterface = null)
    {
        if (!preg_match_all($routeInterface->getPattern(), $uri, $match)) {
            $uri = str_replace('//', '/', $uri . '/' . implode(array_values($routeInterface->getDefaults())));
            if (!preg_match_all($routeInterface->getPattern(), $uri, $match)) {
                return false;
            }
        }

        return $routeInterface;
    }
}