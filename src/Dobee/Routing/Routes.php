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

    protected static $group = '';

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
        $route = self::createRoute($route, $callback, 'GET');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function post($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'POST');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function put($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'PUT');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function delete($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'DELETE');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function head($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'HEAD');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function options($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'OPTIONS');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $route
     * @param $callback
     * @return \Dobee\Routing\Route
     */
    public static function any($route, $callback)
    {
        $route = self::createRoute($route, $callback, 'ANY');

        self::getRouter()->setRoute($route);

        return $route;
    }

    /**
     * @param $group
     * @param $closure
     * @return void
     */
    public static function group($group, $closure)
    {
        if (!is_callable($closure)) {
            throw new InvalidArgumentException(sprintf('Arguments invalid %s by 2 arg.', gettype($closure)));
        }

        self::$group = $group;

        $closure();

        self::$group = '';
    }

    /**
     * @param string $route
     * @param        $callback
     * @param string $method
     * @return \Dobee\Routing\Route
     */
    public static function createRoute($route, $callback, $method)
    {
        $name = '';

        if (is_array($route)) {
            $name = isset($route['name']) ? $route['name'] : '';
            $route = $route[0];
        }

        $route = self::$group . $route;

        return new \Dobee\Routing\Route($route, $name, array(), array($method), array(), array(), $callback);
    }

    /**
     * @param $name
     * @return \Dobee\Routing\RouteInterface
     */
    public static function getRoute($name)
    {
        return self::getRouter()->getRoute($name);
    }
}