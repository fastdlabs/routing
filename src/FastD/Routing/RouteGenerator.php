<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/11/27
 * Time: 上午11:00
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

use FastD\Routing\Exception\RouteException;

/**
 * Class RouteGenerator
 *
 * @package FastD\Routing
 */
class RouteGenerator
{
    /**
     * @param Route $route
     * @param array $parameters
     * @param null  $format
     * @return string
     * @throws RouteException
     */
    public static function generateUrl(Route $route, array $parameters = [], $format = null)
    {
        $parameters = array_merge($route->getDefaults(), $parameters);

        if ($format && in_array($format, $route->getFormats())) {
            $format = '.' . $format;
        } else {
            $format = '';
        }

        $host = '' == $route->getHost() ? '' : $route->getSchema() . '://' . $route->getHost();

        if (array() === $route->getParameters()) {
            return $host . $route->getPath() . $format . (array() === $parameters ? '' : '?' . http_build_query($parameters));
        }

        $replacer = $parameters;
        $keys = array_keys($parameters);
        $search = array_map(
            function ($name) use (&$parameters) {
                unset($parameters[$name]);

                return '{' . $name . '}';
            },
            $keys
        );

        unset($keys);

        $routeUrl = str_replace($search, $replacer, $route->getPath());

        if (!preg_match($route->getPathRegex(), $routeUrl, $match)) {
            throw new RouteException(
                sprintf(
                    'Route "%s" generator fail. Your should set route parameters ["%s"] value.',
                    $route->getName(),
                    implode('", "', array_keys($route->getParameters()))
                ), 500
            );
        }

        return $host . $routeUrl . $format . (array() === $parameters ? '' : '?' . http_build_query($parameters));
    }
}