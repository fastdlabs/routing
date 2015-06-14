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
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_ANY = 'ANY';

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
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_GET]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function post($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_POST]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function put($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_PUT]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function delete($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_DELETE]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function head($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_HEAD]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function options($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_OPTIONS]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function trace($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_TRACE]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function patch($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_PATCH]);
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function any($route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, [Routes::METHOD_ANY]);
    }

    /**
     * @param array $methods
     * @param       $route
     * @param       $callback
     * @return \Dobee\Routing\Route
     */
    public static function match(array $methods = [], $route, $callback)
    {
        return self::getRouter()->createRoute($route, $callback, $methods);
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
}