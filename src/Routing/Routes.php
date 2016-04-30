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
     * @var \FastD\Routing\Router
     */
    protected static $router;

    /**
     * Single mode.
     */
    private function __construct(){}

    private function __clone(){}

    /**
     * @return \FastD\Routing\Router
     */
    public static function getRouter()
    {
        if (null === self::$router) {
            self::$router = new \FastD\Routing\Router();
        }

        return self::$router;
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function get($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'GET', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function post($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'POST', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function put($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'PUT', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function delete($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'DELETE', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function patch($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'PATCH', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $name
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function any($name, $path, $callback, $defaults = [], $requirements = [])
    {
        return self::getRouter()->addRoute($name, 'ANY', $path, $callback, $defaults, $requirements);
    }

    /**
     * @param $group
     * @param Closure $closure
     * @return void
     */
    public static function group($group, \Closure $closure)
    {
        self::getRouter()->group($group, $closure);
    }
}