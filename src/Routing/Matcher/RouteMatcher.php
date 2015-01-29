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
        if (!preg_match($routeInterface->getPattern(), $uri, $match)) {
            $args = array_slice(
                $routeInterface->getArguments(),
                substr_count(rtrim($uri, '/'), '/') - count($routeInterface->getArguments())
            );
            $defaults = $this->filter($routeInterface->getDefaults(), $args);
            $uri = str_replace('//', '/', $uri . '/' . implode('/', array_values($defaults)));
            if (!preg_match($routeInterface->getPattern(), $uri, $match)) {
                return false;
            }
        }
        array_shift($match);

        $parameters = array_combine(array_values($routeInterface->getArguments()), $match);

        $routeInterface->setParameters($parameters);

        return $routeInterface;
    }

    public function filter($defaults, $args)
    {
        $parameters = array();

        foreach ($args as $val) {
            if (isset($defaults[$val])) {
                $parameters[$val] = $defaults[$val];
            }
        }
        unset($defaults, $args);

        return $parameters;
    }
}