<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午10:08
 */

namespace Dobee\Routing\Generator;

use Dobee\Routing\RouteException;
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
     * @param bool           $suffix
     * @return string
     * @throws RouteException
     */
    public static function generateUrl(RouteInterface $route, array $parameters = array(), $suffix = false)
    {
        $parameters = array_merge($route->getDefaults(), $parameters);

        $format = '';

        if ($suffix) {

            $formats = $route->getFormats();

            $format = array_shift($formats);

            $format = '.' . $format;

            unset($formats);
        }

        $host = '';

        if ('' != $route->getHost()) {
            $host = $route->getHttpProtocol() . '://' . $route->getHost();
        }

        if (empty($parameters) || 0 === count($route->getArguments())) {
            return $host . $route->getPath() . $format;
        }

        $replacer = array_map(function ($value) {
            return '{' . $value . '}';
        }, $route->getArguments());

        $routeUrl = str_replace($replacer, $parameters, $route->getPath());

        if (!preg_match_all($route->getPathRegex(), $routeUrl, $match)) {
            throw new RouteException(sprintf('Route "%s" generator fail. Your should set route parameters ["%s"] value.', $route->getName(), implode('", "', $route->getArguments())), 500);
        }


        return $host . $routeUrl . $format;
    }
} 