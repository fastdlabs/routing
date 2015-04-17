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

use Dobee\Routing\RouteCollections;
use Dobee\Routing\RouteInterface;
use Dobee\Routing\RouteException;

/**
 * Class RouteMatcher
 *
 * @package Dobee\Routing\Matcher
 */
class RouteMatcher implements RouteMatcherInterface
{
    /**
     * @param                          $path
     * @param RouteCollections $collections
     * @return RouteInterface
     * @throws RouteException
     */
    public static function match($path, RouteCollections $collections = null)
    {
        try {
            return $collections->getRoute(ltrim($path, '/'));
        } catch (\Exception $e) {
            foreach ($collections as $route) {
                try {
                    return self::matchRequestRoute($path, $route);
                } catch (RouteException $e) {

                }
            }
            throw $e;
        }
    }

    /**
     * @param                $uri
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public static function matchRequestRoute($uri, RouteInterface $route = null)
    {
        if (!preg_match($route->getPathRegex(), $uri, $match)) {

            $args = array_slice(
                $route->getArguments(),
                substr_count($uri, '/') - substr_count($route->getRoute(), '/')
            );

            $defaults = self::filter($route->getDefaults(), $args);
            if (!empty($defaults)) {
                $uri = str_replace('//', '/', $uri . '/' . implode('/', array_values($defaults)));
            }

            if (!preg_match($route->getPathRegex(), $uri, $match)) {
                throw new RouteException(sprintf('Route "%s" is not found.', $uri));
            }
        }

        $arguments = $route->getArguments();

        $defaults = $route->getDefaults();

        $parameters = array();

        foreach ($arguments as $value) {
            $default = isset($defaults[$value]) ? $defaults[$value] : null;
            $parameters[$value] = isset($match[$value]) ? $match[$value] : $default;
        }

        $route->setParameters($parameters);

        return $route;
    }

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return bool
     * @throws RouteException
     */
    public static function matchRequestMethod($method, RouteInterface $route)
    {
        if (in_array('ANY', $route->getMethod()) || in_array($method, $route->getMethod())) {
            return true;
        }

        throw new RouteException(sprintf('Route "%s" request method must to be ["%s"]', $route->getName(), implode('", "', $route->getMethod())));
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return bool
     * @throws RouteException
     */
    public static function matchRequestFormat($format = 'php', RouteInterface $route)
    {
        if (in_array(empty($format) ? 'php' : $format, $route->getFormat())) {
            return true;
        }

        throw new RouteException(sprintf('Route "%s" request format must to be ["%s"]', $route->getName(), implode('", "', $route->getFormat())));
    }

    /**
     * @param array $defaults
     * @param array $args
     * @return array
     */
    public static function filter(array $defaults, array $args)
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