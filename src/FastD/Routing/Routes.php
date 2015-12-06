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

    public static function get($path, $callback)
    {
        return self::createRoute($path, $callback, ['GET']);
    }

    public static function post($path, $callback)
    {
        return self::createRoute($path, $callback, ['POST']);
    }

    public static function put($path, $callback)
    {
        return self::createRoute($path, $callback, ['PUT']);
    }

    public static function delete($path, $callback)
    {
        return self::createRoute($path, $callback, ['DELETE']);
    }

    public static function head($path, $callback)
    {
        return self::createRoute($path, $callback, ['HEAD']);
    }

    public static function options($path, $callback)
    {
        return self::createRoute($path, $callback, ['OPTIONS']);
    }

    public static function trace($path, $callback)
    {
        return self::createRoute($path, $callback, ['TRACE']);
    }

    public static function patch($path, $callback)
    {
        return self::createRoute($path, $callback, ['PATCH']);
    }

    public static function any($path, $callback)
    {
        return self::createRoute($path, $callback);
    }

    public static function match(array $methods = [], $path, $callback)
    {
        return self::createRoute($path, $callback, $methods);
    }

    public static function createRoute($path, $callback, $methods = ['ANY'])
    {
        $name = $path;

        if (is_array($path) && isset($path['name'])) {
            $name = $path['name'];
            $path = $path[0];
        }

        return self::getRouter()->addRoute($name, $path, $callback, [], [], $methods);
    }

    public static function group($group, \Closure $closure)
    {
        self::with($group, $closure);
    }

    public static function with($group, \Closure $closure)
    {
        self::getRouter()->with($group, $closure);
    }
}