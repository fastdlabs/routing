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
     * @param                  $path
     * @param RouteCollections $collections
     * @return RouteInterface|mixed
     * @throws RouteException
     * @throws \Exception
     */
    public static function match($path, RouteCollections $collections = null)
    {
        try {
            return self::extract($path, $collections->getRoute($path));
        } catch (RouteException $e) {
            foreach ($collections as $route) {
                return self::matchRequestRoute($path, $route);
            }

            throw $e;
        }
    }

    /**
     * @param string          $path
     * @param RouteInterface  $route
     * @return RouteInterface
     * @throws RouteException
     */
    public static function matchRequestRoute($path, RouteInterface $route = null)
    {
        if (!preg_match($route->getPathRegex(), $path, $match)) {

            $args = array_slice(
                $route->getArguments(),
                substr_count($path, '/') - substr_count($route->getRoute(), '/')
            );

            $defaults = self::fill($route->getDefaults(), $args);

            $defaultsUri = $path;

            if (!empty($defaults)) {
                $defaultsUri = str_replace('//', '/', $path . '/' . implode('/', array_values($defaults)));
            }

            if (!preg_match($route->getPathRegex(), $defaultsUri, $match)) {
                throw new RouteException(sprintf('Route "%s" is not found.', $path), 404);
            }
        }

        return self::setParameters($route, $match);
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

        throw new RouteException(sprintf(
            'Route "%s" request method must to be ["%s"]',
            $route->getName(),
            implode('", "', $route->getMethod())
        ), 404);
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

        throw new RouteException(sprintf(
            'Route "%s" request format must to be ["%s"]',
            $route->getName(),
            implode('", "', $route->getFormat())
        ), 403);
    }

    /**
     * @param RouteInterface $route
     * @param array          $match
     * @return RouteInterface
     */
    protected static function setParameters(RouteInterface $route, array $match)
    {
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
     * @param array $defaults
     * @param array $args
     * @return array
     */
    protected static function fill(array $defaults, array $args)
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

    /**
     * @param                $path
     * @param RouteInterface $route
     * @return RouteInterface
     */
    protected static function extract($path, RouteInterface $route)
    {
        $arguments = $route->getArguments();

        if (empty($arguments)) {
            return $route;
        }

        return self::matchRequestRoute($path, $route);
    }
}