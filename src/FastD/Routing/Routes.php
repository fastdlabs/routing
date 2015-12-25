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

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function get($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['GET'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function post($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['POST'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function put($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['PUT'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function delete($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['DELETE'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function head($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['HEAD'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function options($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['OPTIONS'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function trace($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['TRACE'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function patch($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, ['PATCH'], $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function any($path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, $defaults, $requirements);
    }

    /**
     * @param array $methods
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function match(array $methods = [], $path, $callback, $defaults = [], $requirements = [])
    {
        return self::createRoute($path, $callback, $methods, $defaults, $requirements);
    }

    /**
     * @param       $path
     * @param       $callback
     * @param array $methods
     * @param array $defaults
     * @param array $requirements
     * @return \FastD\Routing\Route
     */
    public static function createRoute($path, $callback, $methods = ['ANY'], $defaults = [], $requirements = [])
    {
        $name = $path;

        if (is_array($path) && isset($path['name'])) {
            $name = $path['name'];
            $path = $path[0];
        }

        return self::getRouter()->addRoute($name, $path, $callback, $defaults, $requirements, $methods);
    }

    /**
     * @param         $group
     * @param Closure $closure
     */
    public static function group($group, \Closure $closure)
    {
        self::with($group, $closure);
    }

    /**
     * @param         $group
     * @param Closure $closure
     */
    public static function with($group, \Closure $closure)
    {
        self::getRouter()->with($group, $closure);
    }
}