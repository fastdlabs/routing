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
        return self::getRouter()->get($path, $callback);
    }

    public static function post($path, $callback)
    {
        return self::getRouter()->post($path, $callback);
    }

    public static function put($path, $callback)
    {
        return self::getRouter()->put($path, $callback);
    }

    public static function delete($path, $callback)
    {
        return self::getRouter()->delete($path, $callback);
    }

    public static function head($path, $callback)
    {
        return self::getRouter()->head($path, $callback);
    }

    public static function options($path, $callback)
    {
        return self::getRouter()->createRoute($path, $callback);
    }

    public static function trace($path, $callback)
    {
        return self::getRouter()->trace($path, $callback);
    }

    public static function patch($path, $callback)
    {
        return self::getRouter()->patch($path, $callback);
    }

    public static function any($path, $callback)
    {
        return self::getRouter()->any($path, $callback);
    }

    public static function match(array $methods = [], $path, $callback)
    {
        return self::getRouter()->match($methods, $path, $callback);
    }

    public static function createRoute($path, $callback)
    {
        $route = self::getRouter()->createRoute($path, $callback);

        if (is_array($path) && isset($path['name'])) {
            $route->setName($path['name']);
        }

        return $route;
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