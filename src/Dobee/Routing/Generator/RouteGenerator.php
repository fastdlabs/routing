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
    public function generateUrl(RouteInterface $route, array $parameters = array())
    {
        $requirement = $route->getArguments();

        $defaults = (null == ($defaults = $route->getDefaults())) ? $parameters : array_merge($defaults, $parameters);

        if (!empty($requirement) && empty($defaults)) {
            throw new RouteGenerateException(sprintf('Route "%s" parameter ["%s"] is null or empty.', $route->getName(), implode('", "', $route->getArguments())));
        }

        $replacer = array_map(function ($value) {
            return '{' . $value . '}';
        }, $requirement);

        $routeUrl = str_replace($replacer, $defaults, $route->getRoute());

        if (!preg_match_all($route->getPattern(), $routeUrl, $match)) {
            throw new RouteGenerateException(sprintf('Route "%s" generator fail. Your should set route parameters ["%s"] value.', $route->getName(), implode('", "', $route->getArguments())));
        }

        if (is_array(($format = $route->getFormat()))) {
            $format = reset($format);
        }

        return $routeUrl . (empty($format) ? '' : ('.' . $format));
    }
} 