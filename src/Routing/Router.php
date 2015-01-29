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

use Dobee\Routing\Generator\RouteGenerator;

class Router
{
    private $route_collection;

    public function setRouteCollection(RouteCollectionInterface $routeCollectionInterface)
    {
        $this->route_collection = $routeCollectionInterface;

        return $this;
    }

    public function getRouteCollection()
    {
        return $this->route_collection;
    }

    public function generateUrl($name)
    {
        return RouteGenerator::generateUrl($name);
    }
}