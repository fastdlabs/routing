<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午1:51
 */

namespace Dobee\Component\Routing\Collections;

use Dobee\Component\Routing\Route\Route;
use Dobee\Component\Routing\Route\RouteInterface;

/**
 * Class RouteCollections
 *
 * @package Dobee\Component\Routing\Collections
 */
class RouteCollections implements RouteCollectionInterface
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * @param                $name
     * @param RouteInterface $routeInterface
     * @return $this
     * @throws \Exception
     */
    public function add($name, RouteInterface $routeInterface = null)
    {
        if ($name instanceof RouteInterface) {
            if (null === $name->getName()) {
                throw new \LengthException(sprintf("'%s' route name is empty.", get_class($name)));
            }

            $routeInterface = $name;

            $name = $name->getName();
        }

        if (array_key_exists($name, $this->routes)) {
            throw new \Exception(sprintf("Route name '%s' is exists.", $name));
        }

        $this->routes[$name] = $routeInterface;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return null
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param $method
     * @param $arguments
     * @return RouteCollections
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (count($arguments) < 2) {
            throw new \LengthException(sprintf("Arguments error."));
        }

        $route = new Route($arguments[0], $arguments[1], isset($arguments[2]) ? $arguments[2] : array());

        $route->setMethod($method);

        return $this->add($route);
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(static::getInstance(), '__call'), array($method, $args));
    }
}