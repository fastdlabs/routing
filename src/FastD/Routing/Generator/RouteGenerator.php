<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午10:08
 */

namespace FastD\Routing\Generator;

use FastD\Routing\RouteException;
use FastD\Routing\RouteInterface;

/**
 * Class RouteGenerator
 *
 * @package FastD\Routing\Generator
 */
class RouteGenerator
{
    /**
     * @param RouteInterface $route
     * @param array          $parameters
     * @param string         $format
     * @return string
     * @throws RouteException
     */
    public static function generateUrl(RouteInterface $route, array $parameters = array(), $format = '')
    {
        $parameters = array_merge($route->getDefaults(), $parameters);

        $query = '';

        if ($format) {
            if (!in_array($format, $route->getFormats())) {
                $format = array_shift($route->getFormats());
            }

            $format = '.' . $format;
        }

        $host = '';

        if ('' != $route->getDomain()) {
            $host = $route->getSchema() . '://' . $route->getDomain();
        }

        if (0 === count($route->getParameters())) {
            if (!empty($parameters)) {
                $query = '?' . http_build_query($parameters);
            }
            return $host . $route->getPath() . $format . $query;
        }

        $replacer = $parameters;

        $search = array_map(function ($value) use (&$parameters) {
            unset($parameters[$value]);
            return '{' . $value . '}';
        }, $route->getParameters());

        $routeUrl = str_replace($search, $replacer, $route->getPath());

        if (!preg_match_all($route->getPathRegex(), $routeUrl, $match)) {
            if (!preg_match_all($route->getPathRegex(), $route->getGroup() . $routeUrl, $match)) {
                throw new RouteException(sprintf('Route "%s" generator fail. Your should set route parameters ["%s"] value.', $route->getName(), implode('", "', $route->getArguments())), 500);
            }
        }

        if (!empty($parameters)) {
            $query = '?' . http_build_query($parameters);
        }

        return $host . $routeUrl . $format . $query;
    }
} 