<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 上午1:55
 */

namespace Dobee\Component\Routing\Router;

use Dobee\Component\Routing\Route\RouteInterface;

/**
 * Class Router
 *
 * @package Dobee\Component\Routing\Router
 */
class Router implements RouterInterface
{
    /**
     * @var RouteInterface
     */
    private $route;

    /**
     * @param RouteInterface $routeInterface
     */
    public function __construct(RouteInterface $routeInterface)
    {
        $this->route = $routeInterface;
    }

    /**
     * @param $method
     * @param $argument
     */
    public function __call($method, $argument)
    {
        if (method_exists($this, $method)) {
            return $this->$method($argument);
        }

        if (method_exists($this->route, $method)) {
            return $this->route->$method($argument);
        }

        throw new \BadMethodCallException(sprintf("'%s' is not exists", $method));
    }
}