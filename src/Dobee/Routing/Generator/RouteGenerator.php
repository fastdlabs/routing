<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午10:08
 */

namespace Dobee\Routing\Generator;

use Dobee\Routing\RouteCollectionInterface;
use Dobee\Routing\RouteException;

/**
 * Class RouteGenerator
 *
 * @package Dobee\Routing\Generator
 */
class RouteGenerator
{
    /**
     * @var RouteCollectionInterface
     */
    private $collections;

    /**
     * @param RouteCollectionInterface $collectionInterface
     */
    public function __construct(RouteCollectionInterface $collectionInterface)
    {
        $this->collections = $collectionInterface;
    }

    /**
     * @param       $route
     * @param array $parameters
     * @return mixed
     * @throws RouteException
     */
    public function generateUrl($route, array $parameters = array())
    {
        $route = $this->collections->getRoute($route);

        $requirement = $route->getArguments();

        $defaults = (null == ($defaults = $route->getDefaults())) ? $parameters : array_merge($defaults, $parameters);

        if (!empty($requirement) && empty($defaults)) {
            throw new RouteException(sprintf('Route "%s" parameter ["%s"] is null or empty.', $route->getName(), implode('", "', $route->getArguments())));
        }

        $replacer = array_map(function ($value) {
            return '{' . $value . '}';
        }, $route->getArguments());

        $routeUrl = str_replace($replacer, $defaults, $route->getRoute());

        if (!preg_match_all($route->getPattern(), $routeUrl, $match)) {
            throw new RouteException(sprintf('Route "%s" generator fail. Your should set route parameters ["%s"] value.', $route->getName(), implode('", "', $route->getArguments())));
        }

        return $routeUrl . $route->getFormat();
    }
} 