<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/28
 * Time: 下午7:10
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

/**
 * Class Router
 *
 * @package Dobee\Routing
 */
class Router
{
    /**
     * @var RouteCollectionInterface
     */
    private $route_collection;

    /**
     * @param RouteCollectionInterface $routeCollectionInterface
     * @return $this
     */
    public function setRouteCollection(RouteCollectionInterface $routeCollectionInterface)
    {
        $this->route_collection = $routeCollectionInterface;

        return $this;
    }

    /**
     * @return RouteCollectionInterface
     */
    public function getRouteCollection()
    {
        return $this->route_collection;
    }

    /**
     * @param       $name
     * @param array $parameters
     * @return mixed
     */
    public function generateUrl($name, array $parameters = array())
    {
        return $this->route_collection->generateUrl($name, $parameters);
    }

    /**
     * @param                $uri
     * @param RouteInterface $routeInterface
     * @return mixed
     */
    public function match($uri, RouteInterface $routeInterface = null)
    {
        return $this->route_collection->match($uri, $routeInterface);
    }
}