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

namespace FastD\Routing\Matcher;

use FastD\Routing\Exception\RouteException;
use FastD\Routing\Route;
use FastD\Routing\RouteCollectionInterface;

/**
 * Class RouteMatcher
 *
 * @package FastD\Routing\Matcher
 *
 */
class RouteMatcher implements RouteMatcherInterface
{
    /**
     * @param $path
     * @return string
     */
    protected static function getRealPath($path)
    {
        if (false === strpos('.', $path)) {
            return $path;
        }

        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * @param       $path
     * @param Route $route
     * @return bool
     */
    public static function matchRoute($path, Route $route)
    {
        if (!preg_match($route->getPathRegex(), $path, $match)) {
            if (array() === $route->getParameters() || array() === $route->getDefaults()) {
                return false;
            }

            $parameters = array_slice(
                $route->getDefaults(),
                (substr_count($path, '/') - substr_count($route->getPath(), '/'))
            );

            $path = str_replace('//', '/', $path.'/'.implode('/', array_values($parameters)));

            unset($parameters);

            if (!preg_match($route->getPathRegex(), $path, $match)) {
                return false;
            }
        }

        $data = [];
        foreach ($route->getParameters() as $key => $value) {
            if (!empty($match[$key])) {
                $data[$key] = $match[$key];
            }
        }

        $route->mergeParameters($data);

        unset($match);

        return true;
    }

    /**
     * @param       $ip
     * @param Route $route
     * @return bool
     */
    public static function matchIp($ip, Route $route)
    {
        return array() == $route->getIps() || null == $ip ? true : in_array($ip, $route->getIps());
    }

    /**
     * @param       $scheme
     * @param Route $route
     * @return bool
     */
    public static function matchScheme($scheme, Route $route)
    {
        return array() == $route->getSchema() || null == $scheme ? true : in_array($scheme, $route->getSchema());
    }

    /**
     * @param       $host
     * @param Route $route
     * @return bool
     */
    public static function matchHost($host, Route $route)
    {
        return array() == $route->getHost() || null == $host ? true : in_array($host, $route->getHost());
    }

    /**
     * @param       $method
     * @param Route $route
     * @return bool
     */
    public static function matchMethod($method, Route $route)
    {
        return array('ANY') == $route->getMethods() || null == $method ? true : in_array($method, $route->getMethods());
    }

    /**
     * @param       $format
     * @param Route $route
     * @return bool
     */
    public static function matchFormat($format, Route $route)
    {
        return array() == $route->getFormats() || null == $format ? true : in_array($format, $route->getFormats());
    }

    /**
     * @param                          $path
     * @param                          $method
     * @param                          $format
     * @param                          $host
     * @param                          $scheme
     * @param                          $ip
     * @param RouteCollectionInterface $routeCollectionInterface
     * @return Route
     * @throws RouteException
     */
    public static function match(
        $path,
        $method = null,
        $format = null,
        $host = null,
        $scheme = null,
        $ip = null,
        RouteCollectionInterface $routeCollectionInterface
    ) {
        $path = self::getRealPath($path);

        // PHP7 feature
        $route = (function () use ($routeCollectionInterface, $path) : Route {
            try {
                return $routeCollectionInterface->getRoute($path);
            } catch (\Exception $e) {
                foreach ($routeCollectionInterface as $route) {
                    if (self::matchRoute($path, $route)) {
                        return $route;
                    }
                }
            }

            throw $e;
        })();

        if (
            self::matchMethod($method, $route) &&
            self::matchFormat($format, $route) &&
            self::matchScheme($scheme, $route) &&
            self::matchIp($ip, $route)
        ) {
            return $route;
        }

        throw new RouteException(sprintf('Route not match.', $path));
    }
}