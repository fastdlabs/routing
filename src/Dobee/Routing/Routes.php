<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/23
 * Time: 下午12:16
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

class Routes
{
    /**
     * @var \Dobee\Routing\Router
     */
    protected static $router;

    /**
     * Single mode.
     */
    protected function __construct(){}

    /**
     * @return \Dobee\Routing\Router
     */
    public static function getRouter()
    {
        if (null === self::$router) {
            self::$router = new \Dobee\Routing\Router();
        }

        return self::$router;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function get($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'GET');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function post($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'POST');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function put($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'PUT');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function delete($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'DELETE');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function head($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'HEAD');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function options($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'OPTIONS');
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function any($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, 'ANY');
    }

    /**
     * @param $group
     * @param $closure
     * @return void
     */
    public static function group($group, $closure)
    {
        self::getRouter()->group($group, $closure);
    }

    /**
     * @param $route
     * @param $callback
     * @param $methods
     * @return \Dobee\Routing\Route
     */
    public static function create($route, $callback, $methods)
    {
        return self::getRouter()->createRoute($route, $callback, $methods);
    }

    /**
     * @param $name
     * @return \Dobee\Routing\RouteInterface
     */
    public static function getRoute($name)
    {
        return self::getRouter()->getRoute($name);
    }

    /**
     * @return \Dobee\Routing\Router
     */
    final public function __clone()
    {
        return self::getRouter();
    }
}