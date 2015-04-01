<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午10:08
 */

namespace Dobee\Routing\Generator;

use Dobee\Routing\RouteInterface;

/**
 * Class RouteGenerator
 *
 * @package Dobee\Routing\Generator
 */
class RouteGenerator
{
    /**
     * @param RouteInterface $route
     * @param array          $parameters
     * @return string
     * @throws RouteGenerateException
     */
    public static function generateUrl(RouteInterface $route, array $parameters = array())
    {
        $parameters = array_merge($route->getDefaults(), $parameters);

        $formats = $route->getFormat();

        $format = array_shift($formats);

        if (empty($parameters)) {
            return $route->getRoute() . '.' . $format;
        }

        $replacer = array_map(function ($value) {
            return '{' . $value . '}';
        }, $route->getArguments());

        $routeUrl = str_replace($replacer, $parameters, $route->getRoute());

        if (!preg_match_all($route->getPathRegex(), $routeUrl, $match)) {
            throw new RouteGenerateException(sprintf('Route "%s" generator fail. Your should set route parameters ["%s"] value.', $route->getName(), implode('", "', $route->getArguments())));
        }

        return $routeUrl . '.' . $format;
    }
} 