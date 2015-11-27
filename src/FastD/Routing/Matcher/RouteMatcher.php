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

use FastD\Routing\Route;
use FastD\Routing\RouteCollectionInterface;

/**
 * Class RouteMatcher
 *
 * @package FastD\Routing\Matcher
 *
 */
class RouteMatcher
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
     * @param                          $path
     * @param RouteCollectionInterface $routeCollectionInterface
     * @return \FastD\Routing\RouteInterface|mixed
     * @throws \Exception
     */
    public static function match($path, RouteCollectionInterface $routeCollectionInterface)
    {
        $path = self::getRealPath($path);

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
    }

    /**
     * @param       $path
     * @param Route $route
     * @return bool
     */
    public static function matchRoute($path, Route &$route)
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
            $data[$key] = $match[$key];
        }
        $route->mergeParameters($data);

        unset($match);

        return true;
    }
}