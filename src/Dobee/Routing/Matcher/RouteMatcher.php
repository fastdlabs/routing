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

use Dobee\Routing\Collections\RouteCollectionInterface;
use Dobee\Routing\RouteInterface;
use Dobee\Routing\RouteException;
use Dobee\Routing\RouteNotFoundException;

/**
 * Class RouteMatcher
 *
 * @package Dobee\Routing\Matcher
 */
class RouteMatcher implements RouteMatcherInterface
{
    /**
     * @param                          $uri
     * @param RouteCollectionInterface $collection
     * @return mixed
     * @throws RouteNotFoundException
     */
    public function match($uri, RouteCollectionInterface $collection = null)
    {
        foreach ($collection->getRouteCollections() as $route) {
            try {
                $route = $this->matchRequestRoute($uri, $route);
                return $route;
            } catch (RouteNotFoundException $e) {
                continue;
            }
        }

        throw new RouteNotFoundException(sprintf('Route "%s" is not found.', $uri));
    }

    /**
     * @param                $uri
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteNotFoundException
     */
    public function matchRequestRoute($uri, RouteInterface $route = null)
    {
        if (!preg_match($route->getPattern(), $uri, $match)) {
            $args = array_slice(
                $route->getArguments(),
                substr_count(rtrim($uri, '/'), '/') - substr_count(rtrim($route->getPrefix(), '/'), '/') - count($route->getArguments())
            );
            $defaults = $this->filter($route->getDefaults(), $args);
            $uri = str_replace('//', '/', $uri . '/' . implode('/', array_values($defaults)));
            if (!preg_match($route->getPattern(), $uri, $match)) {
                throw new RouteNotFoundException(sprintf('Route "%s" is not found.', $uri));
            }
        }

        array_shift($match);

        $parameters = array_combine(array_values($route->getArguments()), $match);

        $route->setParameters($parameters);

        return $route;
    }

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchRequestMethod($method, RouteInterface $route)
    {
        if ("ANY" === ($methods = $route->getMethod())) {
            return $route;
        }

        if (is_string($methods)) {
            $methods = array($methods);
        }

        if (!in_array($method, $methods)) {
            throw new RouteException(sprintf('Route "%s" request method must to be ["%s"]', $route->getName(), implode('", "', $methods)));
        }

        return $route;
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchRequestFormat($format, RouteInterface $route)
    {
        if ("" == ($formats = $route->getFormat())) {
            return $route;
        }

        if (is_string($formats)) {
            $formats = array($formats);
        }

        if (!in_array($format, $formats)) {
            throw new RouteException(sprintf('Route "%s" request format must to be ["%s"]', $route->getName(), implode('", "', $formats)));
        }

        return $route;
    }

    /**
     * @param $defaults
     * @param $args
     * @return array
     */
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